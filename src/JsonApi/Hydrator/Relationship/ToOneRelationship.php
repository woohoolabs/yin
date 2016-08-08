<?php
namespace WoohooLabs\Yin\JsonApi\Hydrator\Relationship;

use WoohooLabs\Yin\JsonApi\Schema\ResourceIdentifier;

class ToOneRelationship
{
    /**
     * @var null | \WoohooLabs\Yin\JsonApi\Schema\ResourceIdentifier
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
     * @return null | \WoohooLabs\Yin\JsonApi\Schema\ResourceIdentifier $resourceIdentifier
     */
    public function getResourceIdentifier()
    {
        return $this->resourceIdentifier;
    }

    /**
     * Returns true if this relationship is empty, not containing a resource identifier
     * This will be the case when the request want to clear a relationship and sends null as data.
     * @return bool
     */
    public function isEmpty()
    {
        return is_null($this->resourceIdentifier);
    }
}
