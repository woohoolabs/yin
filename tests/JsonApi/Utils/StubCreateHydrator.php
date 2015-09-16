<?php
namespace WoohooLabsTest\Yin\JsonApi\Utils;

use WoohooLabs\Yin\JsonApi\Hydrator\CreateHydratorTrait;

class StubCreateHydrator
{
    use CreateHydratorTrait;

    /**
     * @var \Exception
     */
    private $clientGeneratedIdException;

    /**
     * @var string
     */
    private $generatedId;

    /**
     * @param \Exception $clientGeneratedIdException
     * @param string $generatedId
     */
    public function __construct(\Exception $clientGeneratedIdException = null, $generatedId = "")
    {
        $this->clientGeneratedIdException = $clientGeneratedIdException;
        $this->generatedId = $generatedId;
    }

    /**
     * @param array $data
     * @throws \WoohooLabs\Yin\JsonApi\Exception\ResourceTypeMissing
     * @throws \WoohooLabs\Yin\JsonApi\Exception\ResourceTypeUnacceptable
     */
    protected function validateType($data)
    {
    }

    /**
     * Validates a client-generated ID.
     *
     * If the $clientGeneratedId is not a valid ID for the domain object, then
     * the appropriate exception should be thrown: if it is not well-formed then
     * a ClientGeneratedIdNotSupported exception can be raised, if the ID already
     * exists then a ClientGeneratedIdAlreadyExists exception can be thrown.
     *
     * @param string $clientGeneratedId
     * @throws \WoohooLabs\Yin\JsonApi\Exception\ClientGeneratedIdNotSupported
     * @throws \WoohooLabs\Yin\JsonApi\Exception\ClientGeneratedIdAlreadyExists
     * @throws \Exception
     */
    protected function validateClientGeneratedId($clientGeneratedId)
    {
        if ($this->clientGeneratedIdException !== null) {
            throw $this->clientGeneratedIdException;
        }
    }

    /**
     * Produces a new ID for the domain objects.
     *
     * UUID-s are preferred according to the JSON API specification.
     *
     * @return string
     */
    protected function generateId()
    {
        return $this->generatedId;
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
     * @return array
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
