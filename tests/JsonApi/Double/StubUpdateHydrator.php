<?php

declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Double;

use LogicException;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Hydrator\UpdateHydratorTrait;
use WoohooLabs\Yin\JsonApi\Request\JsonApiRequestInterface;

class StubUpdateHydrator
{
    use UpdateHydratorTrait;

    /** @var bool */
    private $validationException;

    public function __construct(bool $validationException = false)
    {
        $this->validationException = $validationException;
    }

    protected function validateType(array $data, ExceptionFactoryInterface $exceptionFactory): void
    {
    }

    /**
     * @param mixed $domainObject
     * @return mixed|void
     */
    protected function setId($domainObject, string $id)
    {
        $domainObject["id"] = $id;

        return $domainObject;
    }

    protected function validateRequest(JsonApiRequestInterface $request): void
    {
        if ($this->validationException) {
            throw new LogicException();
        }
    }

    /**
     * @param mixed $domainObject
     * @return mixed
     */
    protected function hydrateAttributes($domainObject, array $data)
    {
        return $domainObject;
    }

    /**
     * @param mixed $domainObject
     * @return mixed
     */
    protected function hydrateRelationships($domainObject, array $data, ExceptionFactoryInterface $exceptionFactory)
    {
        return $domainObject;
    }

    /**
     * @param mixed $domainObject
     */
    protected function getRelationshipHydrator($domainObject): array
    {
        return [];
    }

    /**
     * @param mixed $domainObject
     * @return mixed
     */
    protected function doHydrateRelationship(
        $domainObject,
        string $relationshipName,
        callable $hydrator,
        ExceptionFactoryInterface $exceptionFactory,
        ?array $relationshipData,
        ?array $data
    ) {
        return $domainObject;
    }
}
