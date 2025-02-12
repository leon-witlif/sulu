<?php

/*
 * This file is part of Sulu.
 *
 * (c) Sulu GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sulu\Component\DocumentManager\Event;

use Sulu\Component\DocumentManager\Exception\DocumentManagerException;

class FindEvent extends AbstractEvent
{
    use EventOptionsTrait;

    /**
     * @var object
     */
    private $document;

    /**
     * @param string $identifier
     * @param string $locale
     */
    public function __construct(
        private $identifier,
        private $locale,
        array $options = [],
    ) {
        $this->options = $options;
    }

    public function getDebugMessage()
    {
        return \sprintf(
            'i:%s d:%s l:%s',
            $this->identifier,
            $this->document ? \spl_object_hash($this->document) : '<no document>',
            $this->locale ?: '<no locale>'
        );
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->identifier;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @return object
     *
     * @throws DocumentManagerException
     */
    public function getDocument()
    {
        if (!$this->document) {
            throw new DocumentManagerException(\sprintf(
                'No document has been set for the findEvent for "%s". An event listener should have done this.',
                $this->identifier
            ));
        }

        return $this->document;
    }

    /**
     * @param object $document
     */
    public function setDocument($document)
    {
        $this->document = $document;
    }
}
