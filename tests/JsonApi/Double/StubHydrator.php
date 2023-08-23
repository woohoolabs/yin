<?php

declare(strict_types=1);

namespace Devleand\Yin\Tests\JsonApi\Double;

use Devleand\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use Devleand\Yin\JsonApi\Hydrator\AbstractHydrator;
use Devleand\Yin\JsonApi\Request\JsonApiRequestInterface;

class StubHydrator extends AbstractHydrator
{
    /**
     * @var list<string>
     */
    private $acceptedTypes;

    /**
     * @var array<string, callable>
     */
    private $attributeHydrator;

    /**
     * @var array<string, callable>
     */
    private $relationshipHydrator;

    /**
     * @param list<string> $acceptedTypes
     * @param array<string, callable> $attributeHydrator
     * @param array<string, callable> $relationshipHydrator
     */
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
        JsonApiRequestInterface $request,
        ExceptionFactoryInterface $exceptionFactory
    ): void {
    }

    protected function generateId(): string
    {
        return "1";
    }

    /**
     * @return void
     */
    protected function setId($domainObject, string $id)
    {
    }

    protected function validateRequest(JsonApiRequestInterface $request): void
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
