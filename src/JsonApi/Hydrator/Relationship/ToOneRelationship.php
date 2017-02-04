<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Hydrator\Relationship;

use WoohooLabs\Yin\JsonApi\Schema\ResourceIdentifier;

class ToOneRelationship
{
    /**
     * @var \WoohooLabs\Yin\JsonApi\Schema\ResourceIdentifier|null
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
     * @return \WoohooLabs\Yin\JsonApi\Schema\ResourceIdentifier|null $resourceIdentifier
     */
    public function getResourceIdentifier()
    {
        return $this->resourceIdentifier;
    }

    /**
     * Returns true if this relationship is empty, not containing a resource identifier.
     * This will be the case when the request wants to clear a relationship and sends null as data.
     * @return bool
     */
    public function isEmpty()
    {
        return is_null($this->resourceIdentifier);
    }
}
