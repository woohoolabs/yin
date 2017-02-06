<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Double;

use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Hydrator\AbstractHydrator;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;

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
    protected function validateClientGeneratedId(
        $clientGeneratedId,
        RequestInterface $request,
        ExceptionFactoryInterface $exceptionFactory
    ) {
    }

    protected function generateId(): string
    {
        return "1";
    }

    protected function setId($domainObject, $id)
    {
    }

    protected function getAttributeHydrator($domainObject): array
    {
        return $this->attributeHydrator;
    }

    /**
     * @inheritDoc
     */
    protected function getRelationshipHydrator($domainObject): array
    {
        return $this->relationshipHydrator;
    }
}
