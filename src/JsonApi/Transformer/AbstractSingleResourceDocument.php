<?php
namespace WoohooLabs\Yin\JsonApi\Transformer;

use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Schema\Data\SingleResourceData;

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
     * @return \WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface
     */
    protected function instantiateData()
    {
        return new SingleResourceData();
    }

    /**
     * Sets the value of the "data" and "included" properties based on the "resource" property.
     *
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     */
    protected function setData(RequestInterface $request)
    {
        $this->data->addPrimaryResource(
            $this->transformer->transformToResource($this->domainObject, $request, $this->data)
        );
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
        return $this->transformer->transformRelationship(
            $this->domainObject,
            $request,
            $this->data,
            $relationshipName
        );
    }
}
