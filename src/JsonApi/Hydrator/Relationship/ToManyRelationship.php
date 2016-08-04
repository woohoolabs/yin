<?php
namespace WoohooLabs\Yin\JsonApi\Hydrator\Relationship;

use WoohooLabs\Yin\JsonApi\Schema\ResourceIdentifier;

class ToManyRelationship
{
    /**
     * @var ResourceIdentifier[]
     */
    protected $resourceIdentifiers = [];

    /**
     * @param \WoohooLabs\Yin\JsonApi\Schema\ResourceIdentifier[] $resourceIdentifiers
     */
    public function __construct(array $resourceIdentifiers = [])
    {
        foreach ($resourceIdentifiers as $resourceIdentifier) {
            $this->addResourceIdentifier($resourceIdentifier);
        }
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Schema\ResourceIdentifier $resourceIdentifier
     * @return $this
     */
    public function addResourceIdentifier(ResourceIdentifier $resourceIdentifier)
    {
        $this->resourceIdentifiers[] = $resourceIdentifier;
        return $this;
    }

    /**
     * @return ResourceIdentifier[]
     */
    public function getResourceIdentifiers()
    {
        return $this->resourceIdentifiers;
    }

    /**
     * @return array
     */
    public function getResourceIdentifierTypes()
    {
        $types = [];
        foreach ($this->resourceIdentifiers as $resourceIdentifier) {
            /** @var \WoohooLabs\Yin\JsonApi\Schema\ResourceIdentifier $resourceIdentifier */
            $types[] = $resourceIdentifier->getType();
        }

        return $types;
    }

    /**
     * @return array
     */
    public function getResourceIdentifierIds()
    {
        $ids = [];
        foreach ($this->resourceIdentifiers as $resourceIdentifier) {
            /** @var \WoohooLabs\Yin\JsonApi\Schema\ResourceIdentifier $resourceIdentifier */
            $ids[] = $resourceIdentifier->getId();
        }

        return $ids;
    }

    /**
     * Returns true if this relationship is empty, not containing a resource identifier
     * This will be the case when the request want to clear a relationship and sends an empty array as data.
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->resourceIdentifiers);
    }
}
