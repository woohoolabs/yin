<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Document;

use WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface;
use WoohooLabs\Yin\JsonApi\Schema\Data\SingleResourceData;
use WoohooLabs\Yin\JsonApi\Schema\Link\Links;
use WoohooLabs\Yin\JsonApi\Transformer\Transformation;

abstract class AbstractSimpleResourceDocument extends AbstractSuccessfulDocument
{
    /**
     * Provides information about the "links" member of the current document.
     *
     * The method returns a new Links schema object if you want to provide linkage data
     * for the document or null if the member should be omitted from the response.
     */
    abstract public function getLinks(): ?Links;

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
