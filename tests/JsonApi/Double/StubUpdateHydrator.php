<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Double;

use LogicException;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Hydrator\UpdateHydratorTrait;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;

class StubUpdateHydrator
{
    use UpdateHydratorTrait;

    /**
     * @var bool
     */
    private $validationException;

    public function __construct($validationException = false)
    {
        $this->validationException = $validationException;
    }

    protected function validateType($data, ExceptionFactoryInterface $exceptionFactory)
    {
    }

    protected function setId($domainObject, string $id)
    {
        $domainObject["id"] = $id;

        return $domainObject;
    }

    protected function validateRequest(RequestInterface $request)
    {
        if ($this->validationException) {
            throw new LogicException();
        }
    }

    protected function hydrateAttributes($domainObject, array $data)
    {
        return $domainObject;
    }

    protected function hydrateRelationships($domainObject, array $data, ExceptionFactoryInterface $exceptionFactory)
    {
        return $domainObject;
    }

    protected function getRelationshipHydrator($domainObject)
    {
        return [];
    }

    protected function doHydrateRelationship(
        $domainObject,
        $relationshipName,
        callable $hydrator,
        ExceptionFactoryInterface $exceptionFactory,
        $relationshipData,
        $data
    ) {
        return $domainObject;
    }
}
