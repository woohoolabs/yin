<?php
namespace WoohooLabs\Yin\JsonApi\Hydrator\Relationship;

use WoohooLabs\Yin\JsonApi\Schema\ResourceIdentifier;

class ToOneRelationship
{
    /**
     * @var \WoohooLabs\Yin\JsonApi\Schema\ResourceIdentifier
     */
    protected $resourceIdentifier;

    /**
     * @param \WoohooLabs\Yin\JsonApi\Schema\ResourceIdentifier $resourceIdentifier
     */
    public function __construct(ResourceIdentifier $resourceIdentifier = null)
    {
        $this->resourceIdentifier = $resourceIdentifier;
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Schema\ResourceIdentifier $resourceIdentifier
     * @return $this
     */
    public function setResourceIdentifier(ResourceIdentifier $resourceIdentifier)
    {
        $this->resourceIdentifier = $resourceIdentifier;
        return $this;
    }

    /**
     * @return \WoohooLabs\Yin\JsonApi\Schema\ResourceIdentifier $resourceIdentifier
     */
    public function getResourceIdentifier()
    {
        return $this->resourceIdentifier;
    }
}
