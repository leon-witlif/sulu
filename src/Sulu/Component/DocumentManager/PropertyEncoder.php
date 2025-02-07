<?php

/*
 * This file is part of Sulu.
 *
 * (c) Sulu GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sulu\Component\DocumentManager;

use Sulu\Component\DocumentManager\Exception\InvalidLocaleException;

/**
 * Class responsible for encoding properties to PHPCR nodes.
 */
class PropertyEncoder
{
    public function __construct(private NamespaceRegistry $namespaceRegistry)
    {
    }

    public function encode($encoding, $name, $locale)
    {
        return match ($encoding) {
            'system_localized' => $this->localizedSystemName($name, $locale),
            'system' => $this->systemName($name),
            'content_localized' => $this->localizedContentName($name, $locale),
            'content' => $this->contentName($name),
            default => throw new \InvalidArgumentException(\sprintf(
                'Invalid encoding "%s"', $encoding
            )),
        };
    }

    /**
     * @param string $name
     * @param string $locale
     *
     * @return string
     */
    public function localizedSystemName($name, $locale)
    {
        if (null === $locale) {
            throw new InvalidLocaleException($locale);
        }

        return $this->formatLocalizedName('system_localized', $name, $locale);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function systemName($name)
    {
        return $this->formatName('system', $name);
    }

    /**
     * @param string $name
     * @param string $locale
     *
     * @return string
     */
    public function localizedContentName($name, $locale)
    {
        if (null === $locale) {
            throw new InvalidLocaleException($locale);
        }

        return $this->formatLocalizedName('content_localized', $name, $locale);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function contentName($name)
    {
        return $this->formatName('content', $name);
    }

    private function formatName($role, $name)
    {
        $prefix = $this->namespaceRegistry->getPrefix($role);

        if (!$prefix) {
            return $name;
        }

        return \sprintf(
            '%s:%s',
            $prefix,
            $name
        );
    }

    private function formatLocalizedName($role, $name, $locale)
    {
        $prefix = $this->namespaceRegistry->getPrefix($role);

        if (!$prefix) {
            return \sprintf('%s-%s', $locale, $name);
        }

        return \sprintf(
            '%s:%s-%s',
            $prefix,
            $locale,
            $name
        );
    }
}
