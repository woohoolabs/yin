<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Document;

use WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface;
use WoohooLabs\Yin\JsonApi\Schema\Data\SingleResourceData;
use WoohooLabs\Yin\JsonApi\Transformer\Transformation;

abstract class AbstractSimpleResourceDocument extends AbstractSuccessfulDocument
{
    abstract protected function getResource(): array;

    protected function createData(): DataInterface
    {
        return new SingleResourceData();
    }

    protected function fillData(Transformation $transformation): void
    {
        $transformation->data->addPrimaryResource($this->getResource());
    }

    protected function getRelationshipMember(
        string $relationshipName,
        Transformation $transformation,
        array $additionalMeta = []
    ): ?array {
        $relationship = $this->getRelationshipFromResource($this->getResource(), $relationshipName);
        if ($relationship !== null) {
            $transformation->data->addPrimaryResource($relationship);
        }

        return $relationship;
    }

    private function getRelationshipFromResource(array $resource, string $relationshipName): ?array
    {
        if (empty($resource["relationships"][$relationshipName])) {
            return null;
        }

        if (is_array($resource["relationships"][$relationshipName]) === false) {
            return null;
        }

        return $resource["relationships"][$relationshipName];
    }
}
