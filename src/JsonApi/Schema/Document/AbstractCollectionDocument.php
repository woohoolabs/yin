<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema\Document;

use WoohooLabs\Yin\JsonApi\Schema\Data\CollectionData;
use WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface;
use WoohooLabs\Yin\JsonApi\Schema\Link\DocumentLinks;
use WoohooLabs\Yin\JsonApi\Schema\Resource\ResourceInterface;
use WoohooLabs\Yin\JsonApi\Transformer\ResourceDocumentTransformation;
use WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformation;
use WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformer;

abstract class AbstractCollectionDocument extends AbstractResourceDocument
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

    protected function hasItems(): bool
    {
        return empty($this->getItems()) === false;
    }

    protected function getItems(): iterable
    {
        return $this->object;
    }

    /**
     * @internal
     */
    public function getData(ResourceDocumentTransformation $transformation, ResourceTransformer $transformer): DataInterface
    {
        $resourceTransformation = new ResourceTransformation(
            $this->getResource(),
            null,
            "",
            $transformation->request,
            $transformation->basePath,
            $transformation->requestedRelationshipName,
            "",
            $transformation->exceptionFactory
        );
        $data = new CollectionData();

        foreach ($this->getItems() as $item) {
            $resourceTransformation->object = $item;

            $resourceObject = $transformer->transformToResourceObject($resourceTransformation, $data);
            if ($resourceObject !== null) {
                $data->addPrimaryResource($resourceObject);
            }
        }

        return $data;
    }

    public function getRelationshipData(
        ResourceDocumentTransformation $transformation,
        ResourceTransformer $transformer,
        DataInterface $data
    ): ?array {
        return null;
    }
}
