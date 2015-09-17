<?php
namespace WoohooLabsTest\Yin\JsonApi\Utils;

use WoohooLabs\Yin\JsonApi\Hydrator\AbstractHydrator;

class StubHydrator extends AbstractHydrator
{
    /**
     * @var string|array
     */
    private $acceptedType;

    /**
     * @var array
     */
    private $attributeHydrator;

    /**
     * @var array
     */
    private $relationshipHydrator;

    /**
     * @param string|array $acceptedType
     * @param array $attributeHydrator
     * @param array $relationshipHydrator
     */
    public function __construct($acceptedType = "", array $attributeHydrator = [], array $relationshipHydrator = [])
    {
        $this->acceptedType = $acceptedType;
        $this->attributeHydrator = $attributeHydrator;
        $this->relationshipHydrator = $relationshipHydrator;
    }

    /**
     * @inheritDoc
     */
    protected function getAcceptedType()
    {
        return $this->acceptedType;
    }

    /**
     * @inheritDoc
     */
    protected function validateClientGeneratedId($clientGeneratedId)
    {
    }

    /**
     * @inheritDoc
     */
    protected function generateId()
    {
        return "1";
    }

    /**
     * @inheritDoc
     */
    protected function setId($domainObject, $id)
    {
    }

    /**
     * @inheritDoc
     */
    protected function getAttributeHydrator($domainObject)
    {
        return $this->attributeHydrator;
    }

    /**
     * @inheritDoc
     */
    protected function getRelationshipHydrator($domainObject)
    {
        return $this->relationshipHydrator;
    }
}
