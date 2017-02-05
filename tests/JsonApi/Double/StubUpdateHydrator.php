<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Double;

use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Hydrator\UpdateHydratorTrait;

class StubUpdateHydrator
{
    use UpdateHydratorTrait;

    protected function validateType($data, ExceptionFactoryInterface $exceptionFactory)
    {
    }

    protected function setId($domainObject, $id)
    {
        $domainObject["id"] = $id;

        return $domainObject;
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
