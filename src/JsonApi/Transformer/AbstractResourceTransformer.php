<?php
namespace WoohooLabs\Yin\JsonApi\Transformer;

use WoohooLabs\Yin\JsonApi\Exception\InclusionUnrecognized;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface;
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
     * @param mixed $domainObject
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface $data
     * @param string $baseRelationshipPath
     * @return array
     */
    public function transformToResource(
        $domainObject,
        RequestInterface $request,
        DataInterface $data,
        $baseRelationshipPath = ""
    ) {
        $result = $this->transformToResourceIdentifier($domainObject);

        if ($result === null) {
            return null;
        }

        // Links
        $this->transformLinksObject($result, $domainObject);

        // Attributes
        $this->transformAttributesObject($result, $domainObject, $request);

        // Relationships
        $this->transformRelationshipsObject($result, $domainObject, $request, $data, $baseRelationshipPath);

        return $result;
    }

    /**
     * Transforms a relationship with a name of $relationshipName of the original resource to a JSON API relationship.
     *
     * @param mixed $domainObject
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface $data
     * @param string $relationshipName
     * @param string $baseRelationshipPath
     * @return array|null
     */
    public function transformRelationship(
        $domainObject,
        RequestInterface $request,
        DataInterface $data,
        $relationshipName,
        $baseRelationshipPath = ""
    ) {
        $relationships = $this->getRelationships($domainObject);
        if (empty($relationships) === true) {
            return null;
        }

        return $this->transformRelationshipObject(
            $relationships,
            $relationshipName,
            $domainObject,
            $request,
            $data,
            $this->getType($domainObject),
            $baseRelationshipPath,
            $this->getDefaultRelationships($domainObject)
        );
    }

    /**
     * @param array $array
     * @param mixed $domainObject
     */
    private function transformLinksObject(array &$array, $domainObject)
    {
        $links = $this->getLinks($domainObject);

        if (empty($links) === false) {
            $array["links"] = $links->transform();
        }
    }

    /**
     * @param array $array
     * @param array $domainObject
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     */
    private function transformAttributesObject(array &$array, $domainObject, RequestInterface $request)
    {
        $attributes = $this->getAttributes($domainObject);
        if (empty($attributes) === false) {
            $array["attributes"] = $this->transformAttributes(
                $attributes,
                $domainObject,
                $request,
                $this->getType($domainObject)
            );
        }
    }

    /**
     * @param array $attributes
     * @param mixed $domainObject
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param string $resourceType
     * @return array
     */
    private function transformAttributes(array $attributes, $domainObject, RequestInterface $request, $resourceType)
    {
        $result = [];

        foreach ($attributes as $name => $attribute) {
            if ($request->isIncludedField($resourceType, $name)) {
                $result[$name] = $attribute($domainObject, $request);
            }
        }

        return $result;
    }

    /**
     * @param array $array
     * @param mixed $domainObject
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface $data
     * @param string $baseRelationshipPath
     */
    private function transformRelationshipsObject(
        array &$array,
        $domainObject,
        RequestInterface $request,
        DataInterface $data,
        $baseRelationshipPath
    ) {
        $relationships = $this->getRelationships($domainObject);

        if (empty($relationships) === false) {
            $array["relationships"] = $this->transformRelationships(
                $relationships,
                $domainObject,
                $request,
                $data,
                $this->getType($domainObject),
                $baseRelationshipPath
            );
        }
    }

    /**
     * @param array $relationships
     * @param mixed $domainObject
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface $data
     * @param string $resourceType
     * @param string $baseRelationshipPath
     * @return array
     */
    private function transformRelationships(
        array $relationships,
        $domainObject,
        RequestInterface $request,
        DataInterface $data,
        $resourceType,
        $baseRelationshipPath
    ) {
        $this->validateRelationships($request, $baseRelationshipPath, $relationships);

        $result = [];
        $defaultRelationships = array_flip($this->getDefaultRelationships($domainObject));

        foreach ($relationships as $relationshipName => $relationshipCallback) {
            $relationship = $this->transformRelationshipObject(
                $relationships,
                $relationshipName,
                $domainObject,
                $request,
                $data,
                $resourceType,
                $baseRelationshipPath,
                $defaultRelationships
            );

            if ($relationship !== null) {
                $result[$relationshipName] = $relationship;
            }
        }

        return $result;
    }

    /**
     * @param array $relationships
     * @param string $relationshipName
     * @param mixed $domainObject
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface $data
     * @param string $resourceType
     * @param string $baseRelationshipPath
     * @param array $defaultRelationships
     * @return array|null
     */
    private function transformRelationshipObject(
        array $relationships,
        $relationshipName,
        $domainObject,
        RequestInterface $request,
        DataInterface $data,
        $resourceType,
        $baseRelationshipPath,
        array $defaultRelationships
    ) {
        if ($request->isIncludedField($resourceType, $relationshipName) === false &&
            $request->isIncludedRelationship($baseRelationshipPath, $relationshipName, $defaultRelationships) === false
        ) {
            return null;
        }

        $relationshipCallback = $relationships[$relationshipName];
        /** @var \WoohooLabs\Yin\JsonApi\Schema\Relationship\AbstractRelationship $relationship */
        $relationship = $relationshipCallback($domainObject, $request);

        return $relationship->transform(
            $request,
            $data,
            $resourceType,
            $baseRelationshipPath,
            $relationshipName,
            $defaultRelationships
        );
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param string $baseRelationshipPath
     * @param array $relationships
     * @throws \WoohooLabs\Yin\JsonApi\Exception\InclusionUnrecognized
     */
    private function validateRelationships(RequestInterface $request, $baseRelationshipPath, array $relationships)
    {
        $requestedRelationships = $request->getIncludedRelationships($baseRelationshipPath);

        $nonExistentRelationships = array_diff($requestedRelationships, array_keys($relationships));
        if (empty($nonExistentRelationships) === false) {
            foreach ($nonExistentRelationships as &$relationship) {
                $relationship = ($baseRelationshipPath ? $baseRelationshipPath . "." : "") . $relationship;
            }

            throw new InclusionUnrecognized($nonExistentRelationships);
        }
    }
}
