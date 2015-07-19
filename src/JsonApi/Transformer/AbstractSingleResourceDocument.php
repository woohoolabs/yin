<?php
namespace WoohooLabs\Yin\JsonApi\Transformer;

use WoohooLabs\Yin\JsonApi\Request\Request;

abstract class AbstractSingleResourceDocument extends AbstractCompoundDocument
{
    /**
     * @var \WoohooLabs\Yin\JsonApi\Transformer\AbstractResourceTransformer
     */
    protected $transformer;

    /**
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractResourceTransformer $transformer
     */
    public function __construct(AbstractResourceTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\Request $request
     */
    protected function setContent(Request $request)
    {
        $this->data = $this->transformer->transformToResource($this->resource, $request, $this->included);
    }

    /**
     * @param string $relationshipName
     * @param \WoohooLabs\Yin\JsonApi\Request\Request $request
     * @return array
     */
    protected function getRelationshipContent($relationshipName, Request $request)
    {
        return $this->transformer->transformRelationship(
            $this->resource,
            $request,
            $this->included,
            $relationshipName
        );
    }
}
