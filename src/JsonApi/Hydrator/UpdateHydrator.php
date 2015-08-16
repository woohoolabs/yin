<?php
namespace WoohooLabs\Yin\JsonApi\Hydrator;

use WoohooLabs\Yin\JsonApi\Exception\ResourceIdMissing;
use WoohooLabs\Yin\JsonApi\Exception\ResourceTypeMissing;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;

abstract class UpdateHydrator extends AbstractHydrator
{
    /**
     * @param string $id
     * @return true
     * @throws \WoohooLabs\Yin\JsonApi\Exception\ResourceIdInvalid
     */
    abstract protected function validateId($id);

    /**
     * @param mixed $resource
     * @param string $id
     * @return mixed|null
     */
    abstract protected function setId($resource, $id);

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @return string|null
     */
    public function getId(RequestInterface $request)
    {
        $data = $request->getBodyData();
        return isset($data["id"]) ? $data["id"] : null;
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param mixed $resource
     * @return mixed
     * @throws \WoohooLabs\Yin\JsonApi\Exception\ResourceTypeMissing
     */
    public function hydrate(RequestInterface $request, $resource)
    {
        $data = $request->getBodyData();
        if ($data === null) {
            throw new ResourceTypeMissing();
        }

        $this->hydrateType($data);
        $this->hydrateId($data, $resource);
        $this->hydrateAttributes($data, $resource);
        $this->hydrateRelationships($data, $resource);

        return $resource;
    }

    /**
     * @param array $data
     * @param mixed $resource
     * @throws \WoohooLabs\Yin\JsonApi\Exception\ResourceIdMissing
     */
    protected function hydrateId($data, &$resource)
    {
        if (isset($data["id"]) === false) {
            throw new ResourceIdMissing();
        }

        $this->validateId($data["id"]);

        $result = $this->setId($resource, $data["id"]);
        if ($result) {
            $resource = $result;
        }
    }
}
