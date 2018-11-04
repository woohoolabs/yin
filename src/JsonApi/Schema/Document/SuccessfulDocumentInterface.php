<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema\Document;

use WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface;
use WoohooLabs\Yin\JsonApi\Schema\Link\DocumentLinks;
use WoohooLabs\Yin\JsonApi\Transformer\SuccessfulDocumentTransformation;
use WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformer;

interface SuccessfulDocumentInterface extends DocumentInterface
{
    /**
     * Provides information about the "links" member of the current document.
     *
     * The method returns a new Links object if you want to provide linkage data
     * for the document or null if the member should be omitted from the response.
     */
    public function getLinks(): ?DocumentLinks;

    public function getRelationshipMember(SuccessfulDocumentTransformation $transformation): array;

    /**
     * @internal
     */
    public function initializeTransformation(SuccessfulDocumentTransformation $transformation): void;

    /**
     * @internal
     */
    public function getData(SuccessfulDocumentTransformation $transformation, ResourceTransformer $transformer): DataInterface;

    /**
     * @internal
     */
    public function clearTransformation(): void;
}
