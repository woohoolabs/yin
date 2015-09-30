<?php
namespace WoohooLabs\Yin\JsonApi\Transformer;

use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Schema\Data\CollectionData;

abstract class AbstractCollectionDocument extends AbstractSuccessfulDocument
{
    /**
     * @var \Traversable|array
     */
    protected $domainObject;

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
     * @return \WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface
     */
    protected function instantiateData()
    {
        return new CollectionData();
    }

    /**
     * Sets the value of the "data" and "included" properties based on the "resource" property.
     *
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     */
    protected function setData(RequestInterface $request)
    {
        foreach ($this->domainObject as $item) {
            $this->data->addPrimaryResource($this->transformer->transformToResource($item, $request, $this->data));
        }
    }

    /**
     * Returns a response whose primary data is a relationship object with $relationshipName name.
     *
     * @param string $relationshipName
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @return array
     */
    protected function getRelationshipContent($relationshipName, RequestInterface $request)
    {
        if (empty($this->domainObject)) {
            return [];
        }

        $result = [];
        foreach ($this->domainObject as $item) {
            $result[] = $this->transformer->transformRelationship(
                $item,
                $request,
                $this->data,
                $relationshipName
            );
        }

        return $result;
    }
}
