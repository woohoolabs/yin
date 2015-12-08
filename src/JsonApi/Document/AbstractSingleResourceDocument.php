<?php
namespace WoohooLabs\Yin\JsonApi\Document;

use WoohooLabs\Yin\JsonApi\Schema\Data\SingleResourceData;
use WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformerInterface;
use WoohooLabs\Yin\JsonApi\Transformer\Transformation;

abstract class AbstractSingleResourceDocument extends AbstractSuccessfulDocument
{
    /**
     * @var \WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformerInterface
     */
    protected $transformer;

    /**
     * @param \WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformerInterface $transformer
     */
    public function __construct(ResourceTransformerInterface $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * Returns the resource ID for the current domain object.
     *
     * It is a shortcut of calling the resource transformer's getId() method.
     *
     * @return string
     */
    public function getResourceId()
    {
        return $this->transformer->getId($this->domainObject);
    }

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
        $transformation->data->addPrimaryResource(
            $this->transformer->transformToResource($transformation, $this->domainObject)
        );
    }

    /**
     * @inheritDoc
     */
    protected function getRelationshipContent(
        $relationshipName,
        Transformation $transformation,
        array $additionalMeta = []
    ) {
        return $this->transformer->transformRelationship(
            $relationshipName,
            $transformation,
            $this->domainObject,
            $additionalMeta
        );
    }
}
