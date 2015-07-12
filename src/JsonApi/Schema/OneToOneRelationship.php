<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

class OneToOneAbstractRelationship extends AbstractRelationship
{
    /**
     * @var \WoohooLabs\Yin\JsonApi\Schema\ResourceIdentifier
     */
    private $data;

    /**
     * @param array $resource
     */
    public function addResource(array $resource)
    {
        $this->data = $resource;
    }
}
