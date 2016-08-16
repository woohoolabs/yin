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
	 * Provides the relationship hydrators.
	 *
	 * The method returns an array of relationship hydrators, where a hydrator is a key-value pair:
	 * the key is the specific relationship name which comes from the request and the value is an
	 * callable which hydrate the previous relationship.
	 * These callables receive the domain object (which will be hydrated), an object representing the
	 * currently processed relationship (it can be a ToOneRelationship or a ToManyRelationship
	 * object), the "data" part of the request and the relationship name as their arguments, and
	 * they should mutate the state of the domain object.
	 * If it is an immutable object or an array (and passing by reference isn't used),
	 * the callable should return the domain object.
	 *
	 * @param mixed $domainObject
	 * @return callable[]
	 */
	abstract protected function getRelationshipHydrator($domainObject);

	/**
	 * @param mixed $domainObject
	 * @param string $relationshipName
	 * @param callable $hydrator
	 * @param ExceptionFactoryInterface $exceptionFactory
	 * @param array|null $relationshipData
	 * @param array|null $data
	 * @return mixed
	 */
	abstract protected function doHydrateRelationship(
		$domainObject,
		$relationshipName,
		callable $hydrator,
		ExceptionFactoryInterface $exceptionFactory,
		$relationshipData,
		$data
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
        $data = $request->getResource();
        if ($data === null) {
            throw $exceptionFactory->createDataMemberMissingException($request);
        }

        $this->validateType($data, $exceptionFactory);
        $domainObject = $this->hydrateIdForUpdate($domainObject, $data, $exceptionFactory);
        $domainObject = $this->hydrateAttributes($domainObject, $data);
        $domainObject = $this->hydrateRelationships($domainObject, $data, $exceptionFactory);

        return $domainObject;
    }

    /**
     * @param string $relationship
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface $exceptionFactory
     * @param mixed $domainObject
     * @return mixed
     * @throws \WoohooLabs\Yin\JsonApi\Exception\RelationshipNotExists
     */
    public function hydrateForRelationshipUpdate(
        $relationship,
        RequestInterface $request,
        ExceptionFactoryInterface $exceptionFactory,
        $domainObject
    ) {
        $relationshipHydrators = $this->getRelationshipHydrator($domainObject);

        if (isset($relationshipHydrators[$relationship]) === false) {
            throw $exceptionFactory->createRelationshipNotExists($relationship);
        }

        $relationshipHydrator = $relationshipHydrators[$relationship];

        return $this->doHydrateRelationship(
            $domainObject,
            $relationship,
            $relationshipHydrator,
            $exceptionFactory,
            $request->getResource(),
            $request->getResource()
        );
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
