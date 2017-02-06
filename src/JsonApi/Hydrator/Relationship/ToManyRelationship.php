<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Hydrator\Relationship;

use WoohooLabs\Yin\JsonApi\Schema\ResourceIdentifier;

class ToManyRelationship
{
    /**
     * @var ResourceIdentifier[]
     */
    protected $resourceIdentifiers = [];

    /**
     * @param ResourceIdentifier[] $resourceIdentifiers
     */
    public function __construct(array $resourceIdentifiers = [])
    {
        foreach ($resourceIdentifiers as $resourceIdentifier) {
            $this->addResourceIdentifier($resourceIdentifier);
        }
    }

    public function addResourceIdentifier(ResourceIdentifier $resourceIdentifier): ToManyRelationship
    {
        $this->resourceIdentifiers[] = $resourceIdentifier;

        return $this;
    }

    /**
     * @return ResourceIdentifier[]
     */
    public function getResourceIdentifiers(): array
    {
        return $this->resourceIdentifiers;
    }

    public function getResourceIdentifierTypes(): array
    {
        $types = [];
        foreach ($this->resourceIdentifiers as $resourceIdentifier) {
            /** @var ResourceIdentifier $resourceIdentifier */
            $types[] = $resourceIdentifier->getType();
        }

        return $types;
    }

    public function getResourceIdentifierIds(): array
    {
        $ids = [];
        foreach ($this->resourceIdentifiers as $resourceIdentifier) {
            /** @var ResourceIdentifier $resourceIdentifier */
            $ids[] = $resourceIdentifier->getId();
        }

        return $ids;
    }

    /**
     * Returns true if this relationship is empty, not containing a resource identifier
     * This will be the case when the request want to clear a relationship and sends an empty array as data.
     */
    public function isEmpty(): bool
    {
        return empty($this->resourceIdentifiers);
    }
}
