<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

class OneToManyRelationship extends Relationship
{
    /**
     * @var array
     */
    private $data;
    
    /**
     * @param \WoohooLabs\Yin\JsonApi\Schema\ResourceIdentifier $resource
     */
    public function addResource(ResourceIdentifier $resource)
    {
        $this->data[] = $resource;
    }
}
