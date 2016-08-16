<?php
namespace WoohooLabsTest\Yin\JsonApi\Utils;

use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Hydrator\UpdateHydratorTrait;

class StubUpdateHydrator
{
    use UpdateHydratorTrait;

    /**
     * @inheritDoc
     */
    protected function validateType($data, ExceptionFactoryInterface $exceptionFactory)
    {
    }

    /**
     * @inheritDoc
     */
    protected function setId($domainObject, $id)
    {
        $domainObject["id"] = $id;

        return $domainObject;
    }

    /**
     * @inheritDoc
     */
    protected function hydrateAttributes($domainObject, array $data)
    {
        return $domainObject;
    }

    /**
     * @inheritDoc
     */
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
