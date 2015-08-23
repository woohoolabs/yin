<?php
namespace WoohooLabs\Yin\JsonApi\Hydrator;

use WoohooLabs\Yin\JsonApi\Exception\ResourceTypeMissing;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;

trait CreateHydratorTrait
{
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
     */
    abstract protected function validateClientGeneratedId($clientGeneratedId);

    /**
     * Produces a new ID for the domain objects.
     *
     * UUID-s are preferred according to the JSON API specification.
     *
     * @return string
     */
    abstract protected function generateId();

    /**
     * Sets the given ID for the domain object.
     *
     * The method mutates the domain object and sets the given ID for it.
     * If it is an immutable object or an array the whole, updated domain
     * object can be returned.
     *
     * @param mixed $domainObject
     * @param string $id
     * @return mixed|null
     */
    abstract protected function setId($domainObject, $id);

    /**
     * Hydrates the domain object from the creating request.
     *
     * The domain object's attributes and relationships are hydrated
     * according to the JSON API specification.
     *
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param mixed $domainObject
     * @return mixed
     * @throws \WoohooLabs\Yin\JsonApi\Exception\ResourceTypeMissing
     */
    public function hydrateForCreate(RequestInterface $request, $domainObject)
    {
        $data = $request->getBodyData();
        if ($data === null) {
            throw new ResourceTypeMissing();
        }

        $this->hydrateType($data);
        $domainObject = $this->hydrateIdForCreate($data, $domainObject);
        $domainObject = $this->hydrateAttributes($data, $domainObject);
        $domainObject = $this->hydrateRelationships($data, $domainObject);

        return $domainObject;
    }

    /**
     * @param array $data
     * @param mixed $domainObject
     * @return mixed
     */
    protected function hydrateIdForCreate($data, $domainObject)
    {
        if (isset($data["id"]) === true) {
            $this->validateClientGeneratedId($data["id"]);
            $id = $data["id"];
        } else {
            $id = $this->generateId();
        }

        $result = $this->setId($domainObject, $id);
        if ($result) {
            $domainObject = $result;
        }

        return $domainObject;
    }
}
