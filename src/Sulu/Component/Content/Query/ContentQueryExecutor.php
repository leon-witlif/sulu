<?php

/*
 * This file is part of Sulu.
 *
 * (c) Sulu GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sulu\Component\Content\Query;

use Jackalope\Query\Row;
use Sulu\Component\Content\Mapper\ContentMapperInterface;
use Sulu\Component\PHPCR\SessionManager\SessionManagerInterface;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * Executes a query over the content.
 */
class ContentQueryExecutor implements ContentQueryExecutorInterface
{
    public function __construct(
        private SessionManagerInterface $sessionManager,
        private ContentMapperInterface $contentMapper,
        private ?Stopwatch $stopwatch = null,
    ) {
    }

    public function execute(
        $webspaceKey,
        $locales,
        ContentQueryBuilderInterface $contentQueryBuilder,
        $flat = true,
        $depth = -1,
        $limit = null,
        $offset = null,
        $moveUp = false,
        $permission = null
    ) {
        if ($this->stopwatch) {
            $this->stopwatch->start('ContentQuery::execute.build-query');
        }

        list($sql2, $fields) = $contentQueryBuilder->build($webspaceKey, $locales);

        if ($this->stopwatch) {
            $this->stopwatch->stop('ContentQuery::execute.build-query');
            $this->stopwatch->start('ContentQuery::execute.execute-query');
        }

        $query = $this->createSql2Query($sql2, $limit, $offset);
        $queryResult = $query->execute();

        if ($this->stopwatch) {
            $this->stopwatch->stop('ContentQuery::execute.execute-query');
            $this->stopwatch->start('ContentQuery::execute.preload-nodes.get-paths');
        }

        // this preloads all node which should are selected in the statement before
        // prevent the system to load each node individual
        $rootDepth = \substr_count($this->sessionManager->getContentPath($webspaceKey), '/');
        $paths = [];
        /** @var Row $row */
        foreach ($queryResult as $row) {
            $pageDepth = \substr_count($row->getPath('page'), '/') - $rootDepth;

            if (null === $depth || $depth < 0 || ($depth > 0 && $pageDepth <= $depth)) {
                $paths[] = $row->getPath('page');
            }
        }

        if ($this->stopwatch) {
            $this->stopwatch->stop('ContentQuery::execute.preload-nodes.get-paths');
            $this->stopwatch->start('ContentQuery::execute.preload-nodes.execute');
        }

        $this->sessionManager->getSession()->getNodes($paths);

        if ($this->stopwatch) {
            $this->stopwatch->stop('ContentQuery::execute.preload-nodes.execute');
            $this->stopwatch->start('ContentQuery::execute.rowsToList');
        }

        $result = $this->contentMapper->convertQueryResultToArray(
            $queryResult,
            $webspaceKey,
            $locales,
            $fields,
            $depth,
            $contentQueryBuilder->getPublished(),
            $permission
        );

        if ($this->stopwatch) {
            $this->stopwatch->stop('ContentQuery::execute.rowsToList');
        }

        if (!$flat) {
            if ($this->stopwatch) {
                $this->stopwatch->start('ContentQuery::execute.build-tree');
            }

            $converter = new ListToTreeConverter($moveUp);
            $result = $converter->convert($result);

            if ($this->stopwatch) {
                $this->stopwatch->stop('ContentQuery::execute.build-tree');
            }
        }

        return $result;
    }

    /**
     * returns a sql2 query.
     */
    private function createSql2Query($sql2, $limit = null, $offset = null)
    {
        $queryManager = $this->sessionManager->getSession()->getWorkspace()->getQueryManager();
        $query = $queryManager->createQuery($sql2, 'JCR-SQL2');

        if ($limit) {
            $query->setLimit($limit);
        }
        if ($offset) {
            $query->setOffset($offset);
        }

        return $query;
    }
}
