<?php

declare(strict_types=1);

namespace Devleand\Yin\JsonApi\Schema\Document;

use Devleand\Yin\JsonApi\Schema\Data\DataInterface;
use Devleand\Yin\JsonApi\Schema\Link\DocumentLinks;
use Devleand\Yin\JsonApi\Transformer\ResourceDocumentTransformation;
use Devleand\Yin\JsonApi\Transformer\ResourceTransformer;

interface ResourceDocumentInterface extends DocumentInterface
{
    /**
     * Provides information about the "links" member of the current document.
     *
     * The method returns a new DocumentLinks object if you want to provide linkage data
     * for the document or null if the member should be omitted from the response.
     */
    public function getLinks(): ?DocumentLinks;

    public function getRelationshipData(
        ResourceDocumentTransformation $transformation,
        ResourceTransformer $transformer,
        DataInterface $data
    ): ?array;

    /**
     * @internal
     */
    public function initializeTransformation(ResourceDocumentTransformation $transformation): void;

    /**
     * @internal
     */
    public function getData(ResourceDocumentTransformation $transformation, ResourceTransformer $transformer): DataInterface;

    /**
     * @internal
     */
    public function clearTransformation(): void;
}
