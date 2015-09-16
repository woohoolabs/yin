<?php
namespace WoohooLabs\Yin\JsonApi\Hydrator\Relationship;

use WoohooLabs\Yin\JsonApi\Schema\ResourceIdentifier;

class ToManyRelationship
{
    /**
     * @var array
     */
    protected $resourceIdentifiers = [];

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
     * @return array
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
}
