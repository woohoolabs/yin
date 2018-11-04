<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Transformer;

use WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface;
use WoohooLabs\Yin\JsonApi\Schema\Relationship\AbstractRelationship;

final class ResourceTransformer
{
    /**
     * Transforms the original resource to a JSON:API resource object.
     */
    public function transformToResourceObject(ResourceTransformation $transformation, DataInterface $data): ?array
    {
        if ($transformation->object === null) {
            return null;
        }

        $transformation->resource->initializeTransformation($transformation);

        $this->transformResourceIdentifier($transformation);
        $this->transformLinksObject($transformation);
        $this->transformAttributesObject($transformation);
        $this->transformRelationshipsObject($transformation, $data);

        $transformation->resource->clearTransformation();

        return $transformation->result;
    }

    /**
     * Transforms the original resource to a JSON:API resource object.
     */
    public function transformToResourceIdentifier(ResourceTransformation $transformation): ?array
    {
        if ($transformation->object === null) {
            return null;
        }

        $transformation->resource->initializeTransformation($transformation);

        $this->transformResourceIdentifier($transformation);

        $transformation->resource->clearTransformation();

        return $transformation->result;
    }

    private function transformResourceIdentifier(ResourceTransformation $transformation): void
    {
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
        $links = $transformation->resource->getLinks($transformation->object);

        if ($links !== null) {
            $transformation->result["links"] = $links->transform();
        }
    }

    private function transformAttributesObject(ResourceTransformation $transformation): void
    {
        $attributes = $transformation->resource->getAttributes($transformation->object);

        foreach ($attributes as $name => $attribute) {
            if ($transformation->request->isIncludedField($transformation->resourceType, $name)) {
                $transformation->result["attributes"][$name] = $attribute($transformation->object, $transformation->request, $name);
            }
        }
    }

    private function transformRelationshipsObject(ResourceTransformation $transformation, DataInterface $data): void
    {
        $relationships = $transformation->resource->getRelationships($transformation->object);
        $defaultRelationships = array_flip($transformation->resource->getDefaultIncludedRelationships($transformation->object));

        $this->validateRelationships($transformation, $relationships);

        foreach ($relationships as $relationshipName => $relationshipCallback) {
            $transformation->currentRelationshipName = $relationshipName;

            if ($transformation->request->isIncludedField($transformation->resourceType, $relationshipName) === false &&
                $transformation->request->isIncludedRelationship($transformation->basePath, $relationshipName, $defaultRelationships) === false
            ) {
                continue;
            }

            $relationshipCallback = $relationships[$relationshipName];
            /** @var AbstractRelationship $relationship */
            $relationship = $relationshipCallback($transformation->object, $transformation->request, $relationshipName);

            $relationshipObject = $relationship->transform($transformation, $this, $data, $defaultRelationships);
            if (empty($relationshipObject) === false) {
                $transformation->result["relationships"][$relationshipName] = $relationshipObject;
            }
        }

        $transformation->currentRelationshipName = "";
    }

    private function validateRelationships(ResourceTransformation $transformation, array $relationships): void
    {
        $requestedRelationships = $transformation->request->getIncludedRelationships($transformation->basePath);

        $nonExistentRelationships = array_diff($requestedRelationships, array_keys($relationships));
        if (empty($nonExistentRelationships) === false) {
            foreach ($nonExistentRelationships as $key => $relationship) {
                $nonExistentRelationships[$key] = ($transformation->basePath ? $transformation->basePath . "." : "") . $relationship;
            }

            throw $transformation->exceptionFactory->createInclusionUnrecognizedException($transformation->request, $nonExistentRelationships);
        }
    }
}
