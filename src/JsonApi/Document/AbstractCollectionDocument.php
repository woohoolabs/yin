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
    protected function createData()
    {
        return new CollectionData();
    }

    protected function hasItems()
    {
        return empty($this->domainObject) === false;
    }

    protected function getItems()
    {
        return $this->domainObject;
    }

    /**
     * @inheritDoc
     */
    protected function fillData(Transformation $transformation)
    {
        foreach ($this->getItems() as $item) {
            $transformation->data->addPrimaryResource($this->transformer->transformToResource($transformation, $item));
        }
    }

    /**
     * @inheritDoc
     */
    protected function getRelationshipContent(
        $relationshipName,
        Transformation $transformation,
        array $additionalMeta = []
    ) {
        if ($this->hasItems() === false) {
            return [];
        }

        $result = [];
        foreach ($this->getItems() as $item) {
            $result[] = $this->transformer->transformRelationship(
                $relationshipName,
                $transformation,
                $item,
                $additionalMeta
            );
        }

        return $result;
    }
}
