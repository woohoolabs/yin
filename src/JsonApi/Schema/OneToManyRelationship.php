<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

class OneToManyAbstractRelationship extends AbstractRelationship
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
