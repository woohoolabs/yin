<?php
namespace WoohooLabsTest\Yin\JsonApi\Utils;

use WoohooLabs\Yin\JsonApi\Hydrator\UpdateHydratorTrait;

class StubUpdateHydrator
{
    use UpdateHydratorTrait;

    /**
     * @inheritDoc
     */
    protected function validateType($data)
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
    protected function hydrateAttributes($domainObject, $data)
    {
        return $domainObject;
    }

    /**
     * @inheritDoc
     */
    protected function hydrateRelationships($domainObject, $data)
    {
        return $domainObject;
    }
}
