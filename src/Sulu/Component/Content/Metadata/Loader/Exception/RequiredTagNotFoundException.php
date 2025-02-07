<?php

/*
 * This file is part of Sulu.
 *
 * (c) Sulu GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sulu\Component\Content\Metadata\Loader\Exception;

/**
 * Thrown when a template does not contain a required property name.
 */
class RequiredTagNotFoundException extends InvalidXmlException
{
    /**
     * @param string $tagName
     */
    public function __construct($template, protected $tagName)
    {
        parent::__construct(
            $template,
            \sprintf(
                'The tag with the name "%s" is required, but was not found in the template "%s"',
                $this->tagName,
                $template
            )
        );
    }
}
