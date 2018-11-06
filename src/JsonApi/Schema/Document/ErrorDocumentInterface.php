<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema\Document;

use WoohooLabs\Yin\JsonApi\Schema\Error\Error;
use WoohooLabs\Yin\JsonApi\Schema\Link\ErrorLinks;

interface ErrorDocumentInterface extends DocumentInterface
{
    /**
     * Provides information about the "links" member of the current document.
     *
     * The method returns a new ErrorLinks object if you want to provide linkage data
     * for the document or null if the member should be omitted from the response.
     */
    public function getLinks(): ?ErrorLinks;

    /**
     * @return Error[]
     */
    public function getErrors(): array;

    public function getStatusCode(?int $statusCode): int;
}
