<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Hydrator\Relationship;

use WoohooLabs\Yin\JsonApi\Schema\ResourceIdentifier;

class ToOneRelationship
{
    /**
     * @var ResourceIdentifier|null
     */
    protected $resourceIdentifier;

    public function __construct(?ResourceIdentifier $resourceIdentifier = null)
    {
        $this->resourceIdentifier = $resourceIdentifier;
    }

    public function setResourceIdentifier(?ResourceIdentifier $resourceIdentifier): ToOneRelationship
    {
        $this->resourceIdentifier = $resourceIdentifier;
        return $this;
    }

    public function getResourceIdentifier(): ?ResourceIdentifier
    {
        return $this->resourceIdentifier;
    }

    /**
     * Returns true if this relationship is empty, not containing a resource identifier.
     * This will be the case when the request wants to clear a relationship and sends null as data.
     */
    public function isEmpty(): bool
    {
        return $this->resourceIdentifier === null;
    }
}
