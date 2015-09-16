<?php
namespace WoohooLabsTest\Yin\JsonApi\Utils;

use WoohooLabs\Yin\JsonApi\Hydrator\UpdateHydratorTrait;

class StubUpdateHydrator
{
    use UpdateHydratorTrait;

    /**
     * @param array $data
     * @throws \WoohooLabs\Yin\JsonApi\Exception\ResourceTypeMissing
     * @throws \WoohooLabs\Yin\JsonApi\Exception\ResourceTypeUnacceptable
     */
    protected function validateType($data)
    {
    }

    /**
     * Sets the given ID for the domain object.
     *
     * The method mutates the domain object and sets the given ID for it.
     * If it is an immutable object or an array the whole, updated domain
     * object can be returned.
     *
     * @param array $domainObject
     * @param string $id
     * @return mixed|null
     */
    protected function setId($domainObject, $id)
    {
        $domainObject["id"] = $id;

        return $domainObject;
    }

    /**
     * @param array $domainObject
     * @param array $data
     * @return array
     */
    protected function hydrateAttributes($domainObject, $data)
    {
        return $domainObject;
    }

    /**
     * @param array $domainObject
     * @param array $data
     * @return array
     */
    protected function hydrateRelationships($domainObject, $data)
    {
        return $domainObject;
    }
}
