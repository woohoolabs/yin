<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Hydrator;

use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Exception\JsonApiExceptionInterface;
use WoohooLabs\Yin\JsonApi\Exception\RelationshipTypeInappropriate;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToManyRelationship;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToOneRelationship;
use WoohooLabs\Yin\JsonApi\Schema\ResourceIdentifier;

trait HydratorTrait
{
    /**
     * Determines which resource types can be accepted by the hydrator.
     *
     * The method should return an array of acceptable resource types. When such a resource is received for hydration
     * which can't be accepted (its type doesn't match the acceptable types of the hydrator), a ResourceTypeUnacceptable
     * exception will be raised.
     *
     * @return string[]
     */
    abstract protected function getAcceptedTypes(): array;

    /**
     * Provides the attribute hydrators.
     *
     * The method returns an array of attribute hydrators, where a hydrator is a key-value pair:
     * the key is the specific attribute name which comes from the request and the value is a
     * callable which hydrates the given attribute.
     * These callables receive the domain object (which will be hydrated), the value of the
     * currently processed attribute, the "data" part of the request and the name of the attribute
     * to be hydrated as their arguments, and they should mutate the state of the domain object.
     * If it is an immutable object or an array (and passing by reference isn't used),
     * the callable should return the domain object.
     *
     * @param mixed $domainObject
     * @return callable[]
     */
    abstract protected function getAttributeHydrator($domainObject): array;

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
    abstract protected function getRelationshipHydrator($domainObject): array;

    /**
     * @throws JsonApiExceptionInterface
     */
    protected function validateType(array $data, ExceptionFactoryInterface $exceptionFactory): void
    {
        if (empty($data["type"])) {
            throw $exceptionFactory->createResourceTypeMissingException();
        }

        $acceptedTypes = $this->getAcceptedTypes();

        if (in_array($data["type"], $acceptedTypes, true) === false) {
            throw $exceptionFactory->createResourceTypeUnacceptableException($data["type"], $acceptedTypes);
        }
    }

    /**
     * @param mixed $domainObject
     * @return mixed
     */
    protected function hydrateAttributes($domainObject, array $data)
    {
        if (empty($data["attributes"])) {
            return $domainObject;
        }

        $attributeHydrator = $this->getAttributeHydrator($domainObject);
        foreach ($attributeHydrator as $attribute => $hydrator) {
            if (array_key_exists($attribute, $data["attributes"]) === false) {
                continue;
            }

            $result = $hydrator($domainObject, $data["attributes"][$attribute], $data, $attribute);
            if ($result) {
                $domainObject = $result;
            }
        }

        return $domainObject;
    }

    /**
     * @param mixed $domainObject
     * @return mixed
     */
    protected function hydrateRelationships($domainObject, array $data, ExceptionFactoryInterface $exceptionFactory)
    {
        if (empty($data["relationships"])) {
            return $domainObject;
        }

        $relationshipHydrator = $this->getRelationshipHydrator($domainObject);
        foreach ($relationshipHydrator as $relationship => $hydrator) {
            if (isset($data["relationships"][$relationship]) === false) {
                continue;
            }

            $domainObject = $this->doHydrateRelationship(
                $domainObject,
                $relationship,
                $hydrator,
                $exceptionFactory,
                $data["relationships"][$relationship],
                $data
            );
        }

        return $domainObject;
    }

    /**
     * @param mixed $domainObject
     * @return mixed
     */
    protected function doHydrateRelationship(
        $domainObject,
        string $relationshipName,
        callable $hydrator,
        ExceptionFactoryInterface $exceptionFactory,
        ?array $relationshipData,
        ?array $data
    ) {
        $relationshipObject = $this->createRelationship(
            $relationshipData,
            $exceptionFactory
        );

        if ($relationshipObject !== null) {
            $result = $this->getRelationshipHydratorResult(
                $relationshipName,
                $hydrator,
                $domainObject,
                $relationshipObject,
                $data,
                $exceptionFactory
            );

            if ($result) {
                $domainObject = $result;
            }
        }

        return $domainObject;
    }

    /**
     * @param mixed $domainObject
     * @param ToOneRelationship|ToManyRelationship $relationshipObject
     * @return mixed
     * @throws RelationshipTypeInappropriate|JsonApiExceptionInterface
     */
    protected function getRelationshipHydratorResult(
        string $relationshipName,
        callable $hydrator,
        $domainObject,
        $relationshipObject,
        ?array $data,
        ExceptionFactoryInterface $exceptionFactory
    ) {
        // Checking if the current and expected relationship types match
        $relationshipType = $this->getRelationshipType($relationshipObject);
        $expectedRelationshipType = $this->getRelationshipType($this->getArgumentTypeHintFromCallable($hydrator));
        if ($expectedRelationshipType !== null && $relationshipType !== $expectedRelationshipType) {
            throw $exceptionFactory->createRelationshipTypeInappropriateException(
                $relationshipName,
                $relationshipType,
                $expectedRelationshipType
            );
        }

        // Returning if the hydrator returns the hydrated domain object
        $value = $hydrator($domainObject, $relationshipObject, $data, $relationshipName);
        if ($value) {
            return $value;
        }

        // Returning the domain object which was mutated but not returned by the hydrator
        return $domainObject;
    }

    protected function getArgumentTypeHintFromCallable(callable $callable): ?string
    {
        $function = &$callable;
        $reflection = new \ReflectionFunction($function);
        $arguments  = $reflection->getParameters();

        if (empty($arguments) === false && isset($arguments[1]) && $arguments[1]->getClass()) {
            return $arguments[1]->getClass()->getName();
        }

        return null;
    }

    /**
     * @param object|string|null $object
     */
    protected function getRelationshipType($object): ?string
    {
        if ($object instanceof ToOneRelationship || $object === ToOneRelationship::class) {
            return "to-one";
        }

        if ($object instanceof ToManyRelationship || $object === ToManyRelationship::class) {
            return "to-many";
        }

        return null;
    }

    /**
     * @return ToOneRelationship|ToManyRelationship|null
     */
    private function createRelationship(?array $relationship, ExceptionFactoryInterface $exceptionFactory)
    {
        if ($relationship === null || array_key_exists("data", $relationship) === false) {
            return null;
        }

        //If this is a request to clear the relationship, we create an empty relationship
        if (is_null($relationship["data"])) {
            $result = new ToOneRelationship();
        } elseif ($this->isAssociativeArray($relationship["data"])) {
            $result = new ToOneRelationship(
                ResourceIdentifier::fromArray($relationship["data"], $exceptionFactory)
            );
        } else {
            $result = new ToManyRelationship();
            foreach ($relationship["data"] as $relationship) {
                $result->addResourceIdentifier(
                    ResourceIdentifier::fromArray($relationship, $exceptionFactory)
                );
            }
        }

        return $result;
    }

    private function isAssociativeArray(array $array): bool
    {
        return (bool) count(array_filter(array_keys($array), 'is_string'));
    }
}
