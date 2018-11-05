<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema\Document;

use WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface;
use WoohooLabs\Yin\JsonApi\Schema\Data\SingleResourceData;
use WoohooLabs\Yin\JsonApi\Schema\Link\DocumentLinks;
use WoohooLabs\Yin\JsonApi\Schema\Resource\ResourceInterface;
use WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformation;
use WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformer;
use WoohooLabs\Yin\JsonApi\Transformer\SuccessfulDocumentTransformation;

abstract class AbstractSingleResourceDocument extends AbstractSuccessfulDocument
{
    /**
     * @var ResourceInterface
     */
    protected $resource;

    /**
     * Provides information about the "links" member of the current document.
     *
     * The method returns a new Links object if you want to provide linkage data
     * for the document or null if the member should be omitted from the response.
     */
    abstract public function getLinks(): ?DocumentLinks;

    public function __construct(ResourceInterface $resource)
    {
        $this->resource = $resource;
    }

    public function getResource(): ResourceInterface
    {
        return $this->resource;
    }

    /**
     * Returns the resource ID for the current domain object.
     *
     * It is a shortcut of calling the resource transformer's getId() method.
     */
    public function getResourceId(): string
    {
        return $this->resource->getId($this->object);
    }

    /**
     * @internal
     */
    public function getData(SuccessfulDocumentTransformation $transformation, ResourceTransformer $transformer): DataInterface
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
    public function getRelationshipMember(SuccessfulDocumentTransformation $transformation): array
    {
        $relationship = $this->resource->transformRelationship(
            $relationshipName,
            $transformation,
            $this->object,
            $additionalMeta
        );

        if ($relationship === null) {
            $relationship = [];
        }

        return $relationship;
    }
}