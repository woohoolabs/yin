<?php
namespace WoohooLabs\Yin\JsonApi\Hydrator;

use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;

trait CreateHydratorTrait
{
    /**
     * @param array $data
     * @param \WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface $exceptionFactory
     * @throws \WoohooLabs\Yin\JsonApi\Exception\ResourceTypeMissing
     * @throws \WoohooLabs\Yin\JsonApi\Exception\ResourceTypeUnacceptable
     * @throws \Exception
     */
    abstract protected function validateType($data, ExceptionFactoryInterface $exceptionFactory);

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
     * @param array $data
     * @param mixed $domainObject
     * @return mixed
     */
    abstract protected function hydrateAttributes($domainObject, array $data);

    /**
     * @param mixed $domainObject
     * @param array $data
     * @param \WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface $exceptionFactory
     * @return mixed
     */
    abstract protected function hydrateRelationships(
        $domainObject,
        array $data,
        ExceptionFactoryInterface $exceptionFactory
    );

    /**
     * Hydrates the domain object from the creating request.
     *
     * The domain object's attributes and relationships are hydrated
     * according to the JSON API specification.
     *
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface $exceptionFactory
     * @param mixed $domainObject
     * @return mixed
     * @throws \Exception
     */
    public function hydrateForCreate(
        RequestInterface $request,
        ExceptionFactoryInterface $exceptionFactory,
        $domainObject
    ) {
        $data = $request->getBodyData();
        if ($data === null) {
            throw $exceptionFactory->createDataMemberMissingException($request);
        }

        $this->validateType($data, $exceptionFactory);
        $domainObject = $this->hydrateIdForCreate($domainObject, $data);
        $domainObject = $this->hydrateAttributes($domainObject, $data);
        $domainObject = $this->hydrateRelationships($domainObject, $data, $exceptionFactory);

        return $domainObject;
    }

    /**
     * @param array $data
     * @param mixed $domainObject
     * @return mixed
     */
    protected function hydrateIdForCreate($domainObject, $data)
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
