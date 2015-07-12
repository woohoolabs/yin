<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

class OneToManyAbstractRelationship extends AbstractRelationship
{
    /**
     * @var array
     */
    private $data;

    /**
     * @param array $resourceIdentifier
     */
    protected function addResource(array $resourceIdentifier)
    {
        $this->data[] = $resourceIdentifier;
    }
}
