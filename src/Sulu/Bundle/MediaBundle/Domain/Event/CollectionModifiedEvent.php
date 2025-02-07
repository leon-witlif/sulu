<?php

/*
 * This file is part of Sulu.
 *
 * (c) Sulu GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sulu\Bundle\MediaBundle\Domain\Event;

use Sulu\Bundle\ActivityBundle\Domain\Event\DomainEvent;
use Sulu\Bundle\MediaBundle\Admin\MediaAdmin;
use Sulu\Bundle\MediaBundle\Entity\Collection;
use Sulu\Bundle\MediaBundle\Entity\CollectionInterface;
use Sulu\Bundle\MediaBundle\Entity\CollectionMeta;

class CollectionModifiedEvent extends DomainEvent
{
    /**
     * @param mixed[] $payload
     */
    public function __construct(
        private CollectionInterface $collection,
        private string $locale,
        private array $payload
    ) {
        parent::__construct();
    }

    public function getCollection(): CollectionInterface
    {
        return $this->collection;
    }

    public function getEventType(): string
    {
        return 'modified';
    }

    public function getEventPayload(): ?array
    {
        return $this->payload;
    }

    public function getResourceKey(): string
    {
        return CollectionInterface::RESOURCE_KEY;
    }

    public function getResourceId(): string
    {
        return (string) $this->collection->getId();
    }

    public function getResourceLocale(): ?string
    {
        return $this->locale;
    }

    public function getResourceTitle(): ?string
    {
        $collectionMeta = $this->getCollectionMeta();

        return $collectionMeta ? $collectionMeta->getTitle() : null;
    }

    public function getResourceTitleLocale(): ?string
    {
        $collectionMeta = $this->getCollectionMeta();

        return $collectionMeta ? $collectionMeta->getLocale() : null;
    }

    private function getCollectionMeta(): ?CollectionMeta
    {
        /** @var CollectionMeta|null $meta */
        $meta = $this->collection->getDefaultMeta();
        foreach ($this->collection->getMeta() as $collectionMeta) {
            if ($collectionMeta->getLocale() === $this->locale) {
                return $collectionMeta;
            }
        }

        return $meta;
    }

    public function getResourceSecurityContext(): ?string
    {
        return MediaAdmin::SECURITY_CONTEXT;
    }

    public function getResourceSecurityObjectType(): ?string
    {
        return Collection::class;
    }
}
