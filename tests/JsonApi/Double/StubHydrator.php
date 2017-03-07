<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Double;

use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Hydrator\AbstractHydrator;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;

class StubHydrator extends AbstractHydrator
{
    /**
     * @var array
     */
    private $acceptedTypes;

    /**
     * @var array
     */
    private $attributeHydrator;

    /**
     * @var array
     */
    private $relationshipHydrator;

    public function __construct(
        array $acceptedTypes = [],
        array $attributeHydrator = [],
        array $relationshipHydrator = []
    ) {
        $this->acceptedTypes = $acceptedTypes;
        $this->attributeHydrator = $attributeHydrator;
        $this->relationshipHydrator = $relationshipHydrator;
    }

    protected function getAcceptedTypes(): array
    {
        return $this->acceptedTypes;
    }

    protected function validateClientGeneratedId(
        string $clientGeneratedId,
        RequestInterface $request,
        ExceptionFactoryInterface $exceptionFactory
    ) {
    }

    protected function generateId(): string
    {
        return "1";
    }

    protected function setId($domainObject, string $id)
    {
    }

    protected function validateRequest(RequestInterface $request, ExceptionFactoryInterface $exceptionFactory)
    {
    }

    protected function getAttributeHydrator($domainObject): array
    {
        return $this->attributeHydrator;
    }

    protected function getRelationshipHydrator($domainObject): array
    {
        return $this->relationshipHydrator;
    }
}
