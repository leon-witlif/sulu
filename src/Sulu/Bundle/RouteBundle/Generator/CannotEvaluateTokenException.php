<?php

/*
 * This file is part of Sulu.
 *
 * (c) Sulu GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sulu\Bundle\RouteBundle\Generator;

/**
 * This exception indicates that a token cannot be evaluated.
 */
class CannotEvaluateTokenException extends \Exception
{
    /**
     * @param string $token
     * @param mixed $entity
     */
    public function __construct(
        private $token,
        private $entity,
        \Exception $previous,
    ) {
        parent::__construct(
            \sprintf(
                'Cannot evaluate token "%s" for entity with type "%s"',
                $token,
                \is_object($entity) ? \get_class($entity) : \gettype($entity)
            ),
            0,
            $previous
        );
    }

    /**
     * Get token.
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Get entity.
     */
    public function getEntity()
    {
        return $this->entity;
    }
}
