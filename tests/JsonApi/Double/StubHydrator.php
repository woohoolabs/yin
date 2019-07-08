<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Double;

use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Hydrator\AbstractHydrator;
use WoohooLabs\Yin\JsonApi\Request\JsonApiRequestInterface;

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
        JsonApiRequestInterface $request,
        ExceptionFactoryInterface $exceptionFactory
    ): void {
    }

    protected function generateId(): string
    {
        return "1";
    }

    /**
     * @param mixed $domainObject
     * @return void
     */
    protected function setId($domainObject, string $id)
    {
    }

    protected function validateRequest(JsonApiRequestInterface $request): void
    {
    }

    /**
     * @param mixed $domainObject
     * @return callable[]
     */
    protected function getAttributeHydrator($domainObject): array
    {
        return $this->attributeHydrator;
    }

    /**
     * @param mixed $domainObject
     * @return callable[]
     */
    protected function getRelationshipHydrator($domainObject): array
    {
        return $this->relationshipHydrator;
    }
}
