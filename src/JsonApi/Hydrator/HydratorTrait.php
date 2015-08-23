<?php
namespace WoohooLabs\Yin\JsonApi\Hydrator;

use WoohooLabs\Yin\JsonApi\Exception\ResourceTypeMissing;
use WoohooLabs\Yin\JsonApi\Exception\ResourceTypeUnacceptable;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToManyRelationship;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToOneRelationship;
use WoohooLabs\Yin\JsonApi\Schema\ResourceIdentifier;

trait HydratorTrait
{
    /**
     * Determines which resource type or types can be accepted by the hydrator.
     *
     * If the hydrator can only accept one type of resources, the method should
     * return a string. If it accepts more types, then it should return an array
     * of strings. When such a resource is received for hydration which can't be
     * accepted (its type doesn't match the acceptable type or types of the hydrator),
     * a ResourceTypeUnacceptable exception will be raised.
     *
     * @return string|array
     */
    abstract protected function getAcceptedType();

    /**
     * Provides the attribute hydrators.
     *
     * The method returns an array of attribute hydrators, where a hydrator is a key-value pair:
     * the key is the specific attribute name which comes from the request and the value is an
     * anonymous function which hydrate the previous attribute.
     * These anonymous functions receive the domain object (which will be hydrated),
     * the value of the currently processed attribute and the "data" part of the request as their
     * arguments, and they should mutate the state of the domain object.
     * If it is an immutable object or an array (and passing by reference isn't used),
     * the anonymous functions should return the domain object.
     * @param mixed $domainObject
     * @return array
     */
    abstract protected function getAttributeHydrator($domainObject);

    /**
     * Provides the relationship hydrators.
     *
     * The method returns an array of relationship hydrators, where a hydrator is a key-value pair:
     * the key is the specific relationship name which comes from the request and the value is an
     * anonymous function which hydrate the previous relationship.
     * These anonymous functions receive the domain object (which will be hydrated),
     * an object representing the currently processed relationship (it can be a ToOneRelationship or
     * a ToManyRelationship object) and the "data" part of the request as their arguments, and they
     * should mutate the state of the domain object.
     * If it is an immutable object or an array (and passing by reference isn't used),
     * the anonymous functions should return the domain object.
     * @param mixed $domainObject
     * @return array
     */
    abstract protected function getRelationshipHydrator($domainObject);

    /**
     * @param array $data
     * @throws \WoohooLabs\Yin\JsonApi\Exception\ResourceTypeMissing
     * @throws \WoohooLabs\Yin\JsonApi\Exception\ResourceTypeUnacceptable
     */
    protected function hydrateType($data)
    {
        if (isset($data["type"]) === false) {
            throw new ResourceTypeMissing();
        }

        if ($data["type"] !== $this->getAcceptedType() && in_array($data["type"], $this->getAcceptedType()) === false) {
            throw new ResourceTypeUnacceptable($data["type"]);
        }
    }

    /**
     * @param array $data
     * @param mixed $domainObject
     * @return mixed
     */
    protected function hydrateAttributes($data, $domainObject)
    {
        if (isset($data["attributes"]) === false) {
            return $domainObject;
        }

        $attributeHydrator = $this->getAttributeHydrator($domainObject);
        foreach ($attributeHydrator as $attribute => $hydrator) {
            if (isset($data["attributes"][$attribute]) === false) {
                continue;
            }

            $result = $hydrator($domainObject, $data["attributes"][$attribute], $data);
            if ($result) {
                $domainObject = $result;
            }
        }

        return $domainObject;
    }

    /**
     * @param array $data
     * @param mixed $domainObject
     * @return mixed
     */
    protected function hydrateRelationships($data, $domainObject)
    {
        if (isset($data["relationships"]) === false) {
            return $domainObject;
        }

        $relationshipHydrator = $this->getRelationshipHydrator($domainObject);
        foreach ($relationshipHydrator as $relationship => $hydrator) {
            if (isset($data["relationships"][$relationship]) === false) {
                continue;
            }

            $relatiopnship = $this->createRelationship($data["relationships"][$relationship]);
            if ($relationship !== null) {
                $result = $hydrator($domainObject, $relatiopnship, $data);
                if ($result) {
                    $domainObject = $result;
                }
            }
        }

        return $domainObject;
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

        if ($this->isAssociativeArray($relationship["data"]) === true) {
            $result = new ToOneRelationship(ResourceIdentifier::fromArray($relationship["data"]));
        } else {
            $result = new ToManyRelationship();
            foreach ($relationship["data"] as $relationship) {
                $result->addResourceIdentifier(ResourceIdentifier::fromArray($relationship));
            }
        }

        return $result;
    }

    /**
     * @param array $array
     * @return bool
     */
    private function isAssociativeArray(array $array)
    {
        return (bool)count(array_filter(array_keys($array), 'is_string'));
    }
}
