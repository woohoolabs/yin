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
     */
    public function addResourceIdentifier(ResourceIdentifier $resourceIdentifier)
    {
        $this->resourceIdentifiers[] = $resourceIdentifier;
    }

    /**
     * @return array
     */
    public function getResourceIdentifiers()
    {
        return $this->resourceIdentifiers;
    }
}
