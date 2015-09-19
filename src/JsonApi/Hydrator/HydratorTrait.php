<?php
namespace WoohooLabs\Yin\JsonApi\Hydrator;

use WoohooLabs\Yin\JsonApi\Exception\RelationshipTypeNotAppropriate;
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
     * These closures receive the domain object (which will be hydrated),
     * the value of the currently processed attribute and the "data" part of the request as their
     * arguments, and they should mutate the state of the domain object.
     * If it is an immutable object or an array (and passing by reference isn't used),
     * the closures should return the domain object.
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
     * These closures receive the domain object (which will be hydrated),
     * an object representing the currently processed relationship (it can be a ToOneRelationship or
     * a ToManyRelationship object) and the "data" part of the request as their arguments, and they
     * should mutate the state of the domain object.
     * If it is an immutable object or an array (and passing by reference isn't used),
     * the closures should return the domain object.
     * @param mixed $domainObject
     * @return array
     */
    abstract protected function getRelationshipHydrator($domainObject);

    /**
     * @param array $data
     * @throws \WoohooLabs\Yin\JsonApi\Exception\ResourceTypeMissing
     * @throws \WoohooLabs\Yin\JsonApi\Exception\ResourceTypeUnacceptable
     */
    protected function validateType($data)
    {
        if (isset($data["type"]) === false) {
            throw new ResourceTypeMissing();
        }

        $acceptedType = $this->getAcceptedType();

        if (is_string($acceptedType) === true && $data["type"] !== $acceptedType) {
            throw new ResourceTypeUnacceptable($data["type"]);
        }

        if (is_array($acceptedType) && in_array($data["type"], $acceptedType) === false) {
            throw new ResourceTypeUnacceptable($data["type"]);
        }
    }

    /**
     * @param mixed $domainObject
     * @param array $data
     * @return mixed
     */
    protected function hydrateAttributes($domainObject, $data)
    {
        if (empty($data["attributes"])) {
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
     * @param mixed $domainObject
     * @param array $data
     * @return mixed
     */
    protected function hydrateRelationships($domainObject, $data)
    {
        if (empty($data["relationships"])) {
            return $domainObject;
        }

        $relationshipHydrator = $this->getRelationshipHydrator($domainObject);
        foreach ($relationshipHydrator as $relationship => $hydrator) {
            if (isset($data["relationships"][$relationship]) === false) {
                continue;
            }

            $relationshipObject = $this->createRelationship($data["relationships"][$relationship]);
            if ($relationshipObject !== null) {
                $result = $this->getRelationshipHydratorResult(
                    $relationship,
                    $hydrator,
                    $domainObject,
                    $relationshipObject,
                    $data
                );
                if ($result) {
                    $domainObject = $result;
                }
            }
        }

        return $domainObject;
    }

    /**
     * @param string $relationshipName
     * @param \Closure $hydrator
     * @param mixed $domainObject
     * @param ToOneRelationship|ToManyRelationship $relationshipObject
     * @param array $data
     * @return mixed
     * @throws \WoohooLabs\Yin\JsonApi\Exception\RelationshipTypeNotAppropriate
     * @throws \Exception
     */
    protected function getRelationshipHydratorResult(
        $relationshipName,
        \Closure $hydrator,
        $domainObject,
        $relationshipObject,
        array $data
    ) {
        // Checking if the current and expected relationship types match
        $relationshipType = $this->getRelationshipType($relationshipObject);
        $expectedRelationshipType = $this->getRelationshipType($this->getArgumentTypeHintFromClosure($hydrator));
        if ($expectedRelationshipType !== null && $relationshipType !== $expectedRelationshipType) {
            throw new RelationshipTypeNotAppropriate($relationshipName, $relationshipType, $expectedRelationshipType);
        }

        // Returning if the hydrator returns the hydrated domain object
        $value = $hydrator($domainObject, $relationshipObject, $data);
        if ($value) {
            return $value;
        }

        // Returning the domain object which was mutated but not returned by the hydrator
        return $domainObject;
    }

    /**
     * @param \Closure $closure
     * @return string|null
     */
    protected function getArgumentTypeHintFromClosure(\Closure $closure)
    {
        $function = &$closure;
        $reflection = new \ReflectionFunction($function);
        $arguments  = $reflection->getParameters();

        if (empty($arguments) === false && isset($arguments[1]) && $arguments[1]->getClass()) {
            return $arguments[1]->getClass()->getName();
        }

        return null;
    }

    /**
     * @param object|string|null $object
     * @return string|null
     */
    protected function getRelationshipType($object)
    {
        if ($object instanceof ToOneRelationship || $object === ToOneRelationship::class) {
            return "to-one";
        } elseif ($object instanceof ToManyRelationship || $object === ToManyRelationship::class) {
            return "to-many";
        }

        return null;
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
