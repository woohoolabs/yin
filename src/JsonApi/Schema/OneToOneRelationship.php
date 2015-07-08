<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

class OneToOneRelationship extends Relationship
{
    /**
     * @var \WoohooLabs\Yin\JsonApi\Schema\ResourceIdentifier
     */
    private $data;

    /**
     * @param \WoohooLabs\Yin\JsonApi\Schema\ResourceIdentifier $resource
     */
    public function setResource(ResourceIdentifier $resource)
    {
        $this->data = $resource;
    }
}
