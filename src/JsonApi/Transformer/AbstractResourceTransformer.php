<?php
namespace WoohooLabs\Yin\JsonApi\Transformer;

use WoohooLabs\Yin\TransformerTrait;

abstract class AbstractResourceTransformer implements ResourceTransformerInterface
{
    use TransformerTrait;

    /**
     * Transforms the original resource to a JSON API resource identifier.
     *
     * @param mixed $domainObject
     * @return array|null
     */
    public function transformToResourceIdentifier($domainObject)
    {
        if ($domainObject === null) {
            return null;
        }

        $result = [
            "type" => $this->getType($domainObject),
            "id" => $this->getId($domainObject),
        ];

        // META
        $meta = $this->getMeta($domainObject);
        if (empty($meta) === false) {
            $result["meta"] = $meta;
        }

        return $result;
    }

    /**
     * Transforms the original resource to a JSON API resource.
     *
     * @param \WoohooLabs\Yin\JsonApi\Transformer\Transformation $transformation
     * @param mixed $domainObject
     * @return array
     */
    public function transformToResource(Transformation $transformation, $domainObject)
    {
        $result = $this->transformToResourceIdentifier($domainObject);

        if ($result === null) {
            return null;
        }

        // Links
        $this->transformLinksObject($result, $domainObject);

        // Attributes
        $this->transformAttributesObject($result, $transformation, $domainObject);

        // Relationships
        $this->transformRelationshipsObject($result, $transformation, $domainObject);

        return $result;
    }

    /**
     * Transforms a relationship with a name of $relationshipName of the original resource to a JSON API relationship.
     *
     * @param string $relationshipName
     * @param \WoohooLabs\Yin\JsonApi\Transformer\Transformation $transformation
     * @param mixed $domainObject
     * @param array $additionalMeta
     * @return array|null
     */
    public function transformRelationship(
        $relationshipName,
        Transformation $transformation,
        $domainObject,
        array $additionalMeta = []
    ) {
        $relationships = $this->getRelationships($domainObject);
        if (empty($relationships) === true) {
            return null;
        }

        return $this->transformRelationshipObject(
            $transformation,
            $domainObject,
            $relationshipName,
            $relationships,
            [],
            $additionalMeta
        );
    }

    /**
     * @param array $array
     * @param mixed $domainObject
     */
    protected function transformLinksObject(array &$array, $domainObject)
    {
        $linksObject = $this->getLinks($domainObject);

        if ($linksObject !== null) {
            $array["links"] = $linksObject->transform();
        }
    }

    /**
     * @param array $array
     * @param \WoohooLabs\Yin\JsonApi\Transformer\Transformation $transformation
     * @param mixed $domainObject
     */
    protected function transformAttributesObject(array &$array, Transformation $transformation, $domainObject)
    {
        $attributes = $this->getAttributes($domainObject);
        $attributesObject = $this->transformAttributes($transformation, $attributes, $domainObject);

        if (empty($attributesObject) === false) {
            $array["attributes"] = $attributesObject;
        }
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Transformer\Transformation $transformation
     * @param callable[] $attributes
     * @param mixed $domainObject
     * @return array
     */
    protected function transformAttributes(Transformation $transformation, array $attributes, $domainObject)
    {
        $result = [];
        $resourceType = $this->getType($domainObject);

        foreach ($attributes as $name => $attribute) {
            if ($transformation->request->isIncludedField($resourceType, $name)) {
                $result[$name] = call_user_func_array($attribute, [$domainObject, $transformation->request, $name]);
            }
        }

        return $result;
    }

    /**
     * @param array $array
     * @param \WoohooLabs\Yin\JsonApi\Transformer\Transformation $transformation
     * @param mixed $domainObject
     */
    protected function transformRelationshipsObject(array &$array, Transformation $transformation, $domainObject)
    {
        $relationships = $this->getRelationships($domainObject);
        $relationshipsObject = $this->transformRelationships($transformation, $domainObject, $relationships);

        if (empty($relationshipsObject) === false) {
            $array["relationships"] = $relationshipsObject;
        }
    }

    /**
     * @param array $relationships
     * @param \WoohooLabs\Yin\JsonApi\Transformer\Transformation $transformation
     * @param mixed $domainObject
     * @param array $relationships
     * @return array
     */
    protected function transformRelationships(Transformation $transformation, $domainObject, array $relationships)
    {
        $this->validateRelationships($transformation, $relationships);

        $result = [];
        $defaultRelationships = array_flip($this->getDefaultIncludedRelationships($domainObject));

        foreach ($relationships as $relationshipName => $relationshipCallback) {
            $relationship = $this->transformRelationshipObject(
                $transformation,
                $domainObject,
                $relationshipName,
                $relationships,
                $defaultRelationships
            );

            if ($relationship !== null) {
                $result[$relationshipName] = $relationship;
            }
        }

        return $result;
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Transformer\Transformation $transformation
     * @param mixed $domainObject
     * @param string $relationshipName
     * @param callable[] $relationships
     * @param array $defaultRelationships
     * @param array $additionalMeta
     * @return array|null
     */
    protected function transformRelationshipObject(
        Transformation $transformation,
        $domainObject,
        $relationshipName,
        array $relationships,
        array $defaultRelationships,
        array $additionalMeta = []
    ) {
        $resourceType = $this->getType($domainObject);

        if ($transformation->request->isIncludedField($resourceType, $relationshipName) === false &&
            $transformation->request->isIncludedRelationship(
                $transformation->basePath,
                $relationshipName,
                $defaultRelationships
            ) === false
        ) {
            return null;
        }

        $relationshipCallback = $relationships[$relationshipName];
        /** @var \WoohooLabs\Yin\JsonApi\Schema\Relationship\AbstractRelationship $relationship */
        $relationship = call_user_func_array($relationshipCallback, [$domainObject, $transformation->request, $relationshipName]);

        return $relationship->transform(
            $transformation,
            $resourceType,
            $relationshipName,
            $defaultRelationships,
            $additionalMeta
        );
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Transformer\Transformation $transformation
     * @param array $relationships
     * @throws \Exception
     */
    protected function validateRelationships(Transformation $transformation, array $relationships)
    {
        $requestedRelationships = $transformation->request->getIncludedRelationships($transformation->basePath);

        $nonExistentRelationships = array_diff($requestedRelationships, array_keys($relationships));
        if (empty($nonExistentRelationships) === false) {
            foreach ($nonExistentRelationships as &$relationship) {
                $relationship = ($transformation->basePath ? $transformation->basePath . "." : "") . $relationship;
            }

            throw $transformation->exceptionFactory->createInclusionUnrecognizedException(
                $transformation->request,
                $nonExistentRelationships
            );
        }
    }
}
