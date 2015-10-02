<?php
namespace WoohooLabs\Yin\JsonApi\Document;

use WoohooLabs\Yin\JsonApi\Schema\Data\CollectionData;
use WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformerInterface;
use WoohooLabs\Yin\JsonApi\Transformer\Transformation;

abstract class AbstractCollectionDocument extends AbstractSuccessfulDocument
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
     * @inheritDoc
     */
    protected function instantiateData()
    {
        return new CollectionData();
    }

    /**
     * @inheritDoc
     */
    protected function fillData(Transformation $transformation)
    {
        foreach ($this->domainObject as $item) {
            $transformation->data->addPrimaryResource($this->transformer->transformToResource($transformation, $item));
        }
    }

    /**
     * @inheritDoc
     */
    protected function getRelationshipContent($relationshipName, Transformation $transformation)
    {
        if (empty($this->domainObject)) {
            return [];
        }

        $result = [];
        foreach ($this->domainObject as $item) {
            $result[] = $this->transformer->transformRelationship($relationshipName, $transformation, $item);
        }

        return $result;
    }
}
