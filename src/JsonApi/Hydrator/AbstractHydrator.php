<?php
namespace WoohooLabs\Yin\JsonApi\Hydrator;

use WoohooLabs\Yin\JsonApi\Exception\ResourceTypeMissing;
use WoohooLabs\Yin\JsonApi\Exception\ResourceTypeNotAcceptable;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToManyRelationship;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToOneRelationship;
use WoohooLabs\Yin\JsonApi\Request\Request;
use WoohooLabs\Yin\JsonApi\Schema\ResourceIdentifier;

abstract class AbstractHydrator
{
    /**
     * @return string|array
     */
    abstract protected function getAcceptedType();

    /**
     * @return array
     */
    abstract protected function getAttributeHydrator();

    /**
     * @return array
     */
    abstract protected function getRelationshipHydrator();

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\Request $request
     * @return string|null
     */
    public function getType(Request $request)
    {
        $data = $request->getBodyData();
        return isset($data["type"]) ? $data["type"] : null;
    }

    /**
     * @param object $data
     * @throws \WoohooLabs\Yin\JsonApi\Exception\ResourceTypeMissing
     * @throws \WoohooLabs\Yin\JsonApi\Exception\ResourceTypeNotAcceptable
     */
    protected function hydrateType($data)
    {
        if (isset($data["type"]) === false) {
            throw new ResourceTypeMissing();
        }

        if ($data["type"] !== $this->getAcceptedType() && in_array($data["type"], $this->getAcceptedType()) === false) {
            throw new ResourceTypeNotAcceptable($data["type"]);
        }
    }

    /**
     * @param object $data
     * @param mixed $resource
     */
    protected function hydrateAttributes($data, &$resource)
    {
        if (isset($data["attributes"]) === false) {
            return;
        }

        $attributeHydrators = $this->getAttributeHydrator();
        foreach ($attributeHydrators as $attribute => $hydrator) {
            if (isset($data["attributes"][$attribute]) === false) {
                continue;
            }

            $result = $hydrator($resource, $data["attributes"][$attribute], $data);
            if ($result) {
                $resource = $result;
            }
        }
    }

    /**
     * @param object $data
     * @param mixed $resource
     */
    protected function hydrateRelationships($data, &$resource)
    {
        if (isset($data["relationships"]) === false) {
            return;
        }

        $relationshipHydrators = $this->getRelationshipHydrator();
        foreach ($relationshipHydrators as $relationship => $hydrator) {
            if (isset($data["relationships"][$relationship]) === false) {
                continue;
            }

            $result = $hydrator($resource, $this->createRelationship($data["relationships"][$relationship]), $data);
            if ($result) {
                $resource = $result;
            }
        }
    }

    /**
     * @param array $relationship
     * @return \WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToOneRelationship|
     * \WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToManyRelationship|null
     */
    private function createRelationship(array $relationship)
    {
        if (isset($relationship["data"]) === false) {
            return null;
        }

        if (is_object($relationship["data"]) === true) {
            $result = new ToOneRelationship(ResourceIdentifier::fromArray($relationship["data"]));
        } else {
            $result = new ToManyRelationship();
            foreach ($relationship["data"] as $relationship) {
                $result->addResourceIdentifier(ResourceIdentifier::fromArray($relationship));
            }
        }

        return $result;
    }
}
