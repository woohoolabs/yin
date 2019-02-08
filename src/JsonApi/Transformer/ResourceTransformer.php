<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Transformer;

use WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface;
use WoohooLabs\Yin\JsonApi\Schema\Relationship\AbstractRelationship;
use function array_diff;
use function array_flip;
use function array_keys;

/**
 * @internal
 */
final class ResourceTransformer
{
    /**
     * Transforms the original resource to a JSON:API resource object.
     */
    public function transformToResourceObject(ResourceTransformation $transformation, DataInterface $data): ?array
    {
        if ($transformation->object === null || $transformation->resource === null) {
            return null;
        }

        $transformation->resource->initializeTransformation(
            $transformation->request,
            $transformation->object,
            $transformation->exceptionFactory
        );

        $this->transformResourceIdentifier($transformation);
        $this->transformLinksObject($transformation);
        $this->transformAttributesObject($transformation);
        $this->transformRelationshipsObject($transformation, $data);

        $transformation->resource->clearTransformation();

        return $transformation->result;
    }

    /**
     * Transforms the original resource to a JSON:API resource identifier.
     */
    public function transformToResourceIdentifier(ResourceTransformation $transformation): ?array
    {
        if ($transformation->object === null || $transformation->resource === null) {
            return null;
        }

        $transformation->resource->initializeTransformation(
            $transformation->request,
            $transformation->object,
            $transformation->exceptionFactory
        );

        $this->transformResourceIdentifier($transformation);

        $transformation->resource->clearTransformation();

        return $transformation->result;
    }

    /**
     * Transforms a relationship of the original resource to a JSON:API relationship.
     */
    public function transformToRelationshipObject(ResourceTransformation $transformation, DataInterface $data): ?array
    {
        if ($transformation->object === null || $transformation->resource === null) {
            return null;
        }

        $relationships = $transformation->resource->getRelationships($transformation->object);
        if (isset($relationships[$transformation->requestedRelationshipName]) === false) {
            throw $transformation->exceptionFactory->createRelationshipNotExistsException($transformation->requestedRelationshipName);
        }

        $defaultRelationships = $transformation->resource->getDefaultIncludedRelationships($transformation->object);

        $transformation->result = $this->transformRelationshipObject(
            $transformation,
            $data,
            $relationships[$transformation->currentRelationshipName],
            $defaultRelationships
        );

        return $transformation->result;
    }

    private function transformResourceIdentifier(ResourceTransformation $transformation): void
    {
        if ($transformation->object === null || $transformation->resource === null) {
            return;
        }

        $type = $transformation->resource->getType($transformation->object);
        $transformation->resourceType = $type;
        $id = $transformation->resource->getId($transformation->object);

        $transformation->result = [
            "type" => $type,
            "id" => $id,
        ];

        $meta = $transformation->resource->getMeta($transformation->object);
        if (empty($meta) === false) {
            $transformation->result["meta"] = $meta;
        }
    }

    private function transformLinksObject(ResourceTransformation $transformation): void
    {
        if ($transformation->object === null || $transformation->resource === null) {
            return;
        }

        $links = $transformation->resource->getLinks($transformation->object);

        if ($links !== null) {
            $transformation->result["links"] = $links->transform();
        }
    }

    private function transformAttributesObject(ResourceTransformation $transformation): void
    {
        if ($transformation->object === null || $transformation->resource === null) {
            return;
        }

        $attributes = $transformation->resource->getAttributes($transformation->object);

        foreach ($attributes as $name => $attribute) {
            if ($transformation->request->isIncludedField($transformation->resourceType, $name)) {
                $transformation->result["attributes"][$name] = $attribute($transformation->object, $transformation->request, $name);
            }
        }
    }

    private function transformRelationshipsObject(ResourceTransformation $transformation, DataInterface $data): void
    {
        if ($transformation->object === null || $transformation->resource === null) {
            return;
        }

        $relationships = $transformation->resource->getRelationships($transformation->object);
        $defaultRelationships = array_flip($transformation->resource->getDefaultIncludedRelationships($transformation->object));

        $this->validateRelationships($transformation, $relationships);

        foreach ($relationships as $relationshipName => $relationshipCallback) {
            $transformation->currentRelationshipName = $relationshipName;
            $relationshipObject = $this->transformRelationshipObject(
                $transformation,
                $data,
                $relationshipCallback,
                $defaultRelationships
            );

            if (empty($relationshipObject) === false) {
                $transformation->result["relationships"][$relationshipName] = $relationshipObject;
            }
        }

        $transformation->currentRelationshipName = "";
    }

    private function transformRelationshipObject(
        ResourceTransformation $transformation,
        DataInterface $data,
        callable $relationshipCallback,
        array $defaultRelationships
    ): ?array {
        $relationshipName = $transformation->currentRelationshipName;

        if ($transformation->request->isIncludedField($transformation->resourceType, $relationshipName) === false &&
            $transformation->request->isIncludedRelationship($transformation->basePath, $relationshipName, $defaultRelationships) === false
        ) {
            return null;
        }

        /** @var AbstractRelationship $relationship */
        $relationship = $relationshipCallback($transformation->object, $transformation->request, $relationshipName);

        return $relationship->transform($transformation, $this, $data, $defaultRelationships);
    }

    private function validateRelationships(ResourceTransformation $transformation, array $relationships): void
    {
        $requestedRelationships = $transformation->request->getIncludedRelationships($transformation->basePath);

        $nonExistentRelationships = array_diff($requestedRelationships, array_keys($relationships));
        if (empty($nonExistentRelationships) === false) {
            foreach ($nonExistentRelationships as $key => $relationship) {
                $nonExistentRelationships[$key] = ($transformation->basePath !== "" ? $transformation->basePath . "." : "") . $relationship;
            }

            throw $transformation->exceptionFactory->createInclusionUnrecognizedException($transformation->request, $nonExistentRelationships);
        }
    }
}
