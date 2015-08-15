<?php
namespace WoohooLabs\Yin\JsonApi\Hydrator;

use WoohooLabs\Yin\JsonApi\Exception\ResourceTypeMissing;
use WoohooLabs\Yin\JsonApi\Request\Request;

abstract class InsertHydrator extends AbstractHydrator
{
    /**
     * @param string $clientGeneratedId
     * @return true
     * @throws \WoohooLabs\Yin\JsonApi\Exception\ClientGeneratedIdNotSupported
     * @throws \WoohooLabs\Yin\JsonApi\Exception\ClientGeneratedIdAlreadyExists
     */
    abstract protected function validateClientGeneratedId($clientGeneratedId);

    /**
     * @return mixed
     */
    abstract protected function generateId();

    /**
     * @param mixed $resource
     * @param string $id
     * @return mixed
     */
    abstract protected function setId($resource, $id);

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\Request $request
     * @return string|null
     */
    public function getClientGeneratedId(Request $request)
    {
        $data = $request->getBodyData();
        return isset($data["id"]) ? $data["id"] : null;
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\Request $request
     * @param mixed $resource
     * @return mixed
     * @throws \WoohooLabs\Yin\JsonApi\Exception\ResourceTypeMissing
     */
    public function hydrate(Request $request, $resource)
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
     * @param object $data
     * @param mixed $resource
     */
    protected function hydrateId($data, &$resource)
    {
        if (isset($data["id"]) === true && $this->validateClientGeneratedId($data["id"]) === true) {
            $id = $data["id"];
        } else {
            $id = $this->generateId();
        }

        $result = $this->setId($resource, $id);
        if ($result) {
            $resource = $result;
        }
    }
}
