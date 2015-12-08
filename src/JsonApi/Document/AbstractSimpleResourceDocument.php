<?php
namespace WoohooLabs\Yin\JsonApi\Document;

use WoohooLabs\Yin\JsonApi\Schema\Data\SingleResourceData;
use WoohooLabs\Yin\JsonApi\Transformer\Transformation;

abstract class AbstractSimpleResourceDocument extends AbstractSuccessfulDocument
{
    /**
     * @return array
     */
    abstract protected function getData();

    /**
     * @inheritDoc
     */
    protected function createData()
    {
        return new SingleResourceData();
    }

    /**
     * @inheritDoc
     */
    protected function fillData(Transformation $transformation)
    {
        $transformation->data->addPrimaryResource($this->getData());
    }

    /**
     * @inheritDoc
     */
    protected function getRelationshipContent(
        $relationshipName,
        Transformation $transformation,
        array $additionalMeta = []
    ) {
        $relationship = $this->getRelationshipFromResource($this->getData(), $relationshipName);
        if ($relationship !== null) {
            $transformation->data->addPrimaryResource($relationship);
        }
    }

    /**
     * @param array $resource
     * @param string $relationshipName
     * @return array|null
     */
    private function getRelationshipFromResource(array $resource, $relationshipName)
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
