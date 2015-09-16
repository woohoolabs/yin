<?php
namespace WoohooLabs\Yin\JsonApi\Hydrator;

use WoohooLabs\Yin\JsonApi\Exception\ResourceIdMissing;
use WoohooLabs\Yin\JsonApi\Exception\ResourceTypeMissing;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;

trait UpdateHydratorTrait
{
    /**
     * @param array $data
     * @throws \WoohooLabs\Yin\JsonApi\Exception\ResourceTypeMissing
     * @throws \WoohooLabs\Yin\JsonApi\Exception\ResourceTypeUnacceptable
     */
    abstract protected function validateType($data);

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
    abstract protected function hydrateAttributes($domainObject, $data);

    /**
     * @param mixed $domainObject
     * @param array $data
     * @return mixed
     */
    abstract protected function hydrateRelationships($domainObject, $data);

    /**
     * Hydrates the domain object from the updating request.
     *
     * The domain object's attributes and relationships are hydrated
     * according to the JSON API specification.
     *
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param mixed $domainObject
     * @return mixed
     * @throws \WoohooLabs\Yin\JsonApi\Exception\ResourceTypeMissing
     */
    public function hydrateForUpdate(RequestInterface $request, $domainObject)
    {
        $data = $request->getBodyData();
        if (empty($data)) {
            throw new ResourceTypeMissing();
        }

        $this->validateType($data);
        $domainObject = $this->hydrateIdForUpdate($domainObject, $data);
        $domainObject = $this->hydrateAttributes($domainObject, $data);
        $domainObject = $this->hydrateRelationships($domainObject, $data);

        return $domainObject;
    }

    /**
     * @param mixed $domainObject
     * @param array $data
     * @return mixed
     * @throws \WoohooLabs\Yin\JsonApi\Exception\ResourceIdMissing
     */
    protected function hydrateIdForUpdate($domainObject, $data)
    {
        if (empty($data["id"])) {
            throw new ResourceIdMissing();
        }

        $result = $this->setId($domainObject, $data["id"]);
        if ($result) {
            $domainObject = $result;
        }

        return $domainObject;
    }
}
