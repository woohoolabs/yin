<?php

declare(strict_types=1);

namespace Devleand\Yin\JsonApi\Schema\Document;

use Devleand\Yin\JsonApi\Schema\Data\DataInterface;
use Devleand\Yin\JsonApi\Schema\Data\SingleResourceData;
use Devleand\Yin\JsonApi\Schema\Resource\ResourceInterface;
use Devleand\Yin\JsonApi\Transformer\ResourceDocumentTransformation;
use Devleand\Yin\JsonApi\Transformer\ResourceTransformation;
use Devleand\Yin\JsonApi\Transformer\ResourceTransformer;

abstract class AbstractSingleResourceDocument extends AbstractResourceDocument
{
    /**
     * @var ResourceInterface
     */
    protected $resource;

    public function __construct(ResourceInterface $resource)
    {
        $this->resource = $resource;
    }

    public function getResource(): ResourceInterface
    {
        $this->resource->initializeTransformation($this->request, $this->object, $this->exceptionFactory);

        return $this->resource;
    }

    /**
     * Returns the resource ID for the current domain object.
     *
     * It is a shortcut of calling the resource transformer's getId() method.
     */
    public function getResourceId(): string
    {
        return $this->getResource()->getId($this->object);
    }

    /**
     * @internal
     */
    public function getData(ResourceDocumentTransformation $transformation, ResourceTransformer $transformer): DataInterface
    {
        $resourceTransformation = new ResourceTransformation(
            $this->getResource(),
            $transformation->object,
            "",
            $transformation->request,
            $transformation->basePath,
            $transformation->requestedRelationshipName,
            "",
            $transformation->exceptionFactory
        );
        $data = new SingleResourceData();

        $resourceObject = $transformer->transformToResourceObject($resourceTransformation, $data);
        if ($resourceObject !== null) {
            $data->addPrimaryResource($resourceObject);
        }

        return $data;
    }

    /**
     * @internal
     */
    public function getRelationshipData(
        ResourceDocumentTransformation $transformation,
        ResourceTransformer $transformer,
        DataInterface $data
    ): ?array {
        $resourceTransformation = new ResourceTransformation(
            $this->getResource(),
            $transformation->object,
            "",
            $transformation->request,
            $transformation->basePath,
            $transformation->requestedRelationshipName,
            $transformation->requestedRelationshipName,
            $transformation->exceptionFactory
        );

        return $transformer->transformToRelationshipObject($resourceTransformation, $data);
    }
}
