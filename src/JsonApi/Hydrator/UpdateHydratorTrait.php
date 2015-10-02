<?php
namespace WoohooLabs\Yin\JsonApi\Hydrator;

use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;

trait UpdateHydratorTrait
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
     * @param mixed $domainObject
     * @param array $data
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
     * Hydrates the domain object from the updating request.
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
    public function hydrateForUpdate(
        RequestInterface $request,
        ExceptionFactoryInterface $exceptionFactory,
        $domainObject
    ) {
        $data = $request->getBodyData();
        if (empty($data)) {
            throw $exceptionFactory->createResourceTypeMissingException();
        }

        $this->validateType($data, $exceptionFactory);
        $domainObject = $this->hydrateIdForUpdate($domainObject, $data, $exceptionFactory);
        $domainObject = $this->hydrateAttributes($domainObject, $data);
        $domainObject = $this->hydrateRelationships($domainObject, $data, $exceptionFactory);

        return $domainObject;
    }

    /**
     * @param mixed $domainObject
     * @param array $data
     * @param \WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface $exceptionFactory
     * @return mixed
     * @throws \Exception
     */
    protected function hydrateIdForUpdate($domainObject, $data, ExceptionFactoryInterface $exceptionFactory)
    {
        if (empty($data["id"])) {
            throw $exceptionFactory->createResourceIdMissingException();
        }

        $result = $this->setId($domainObject, $data["id"]);
        if ($result) {
            $domainObject = $result;
        }

        return $domainObject;
    }
}
