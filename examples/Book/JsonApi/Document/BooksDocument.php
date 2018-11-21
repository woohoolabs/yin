<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Examples\Book\JsonApi\Document;

use WoohooLabs\Yin\Examples\Book\JsonApi\Resource\BookResource;
use WoohooLabs\Yin\JsonApi\Schema\Document\AbstractCollectionDocument;
use WoohooLabs\Yin\JsonApi\Schema\JsonApiObject;
use WoohooLabs\Yin\JsonApi\Schema\Link\DocumentLinks;

class BooksDocument extends AbstractCollectionDocument
{
    public function __construct(BookResource $resource)
    {
        parent::__construct($resource);
    }

    /**
     * Provides information about the "jsonapi" member of the current document.
     *
     * The method returns a new JsonApiObject object if this member should be present or null
     * if it should be omitted from the response.
     */
    public function getJsonApi(): ?JsonApiObject
    {
        return new JsonApiObject("1.1");
    }

    /**
     * Provides information about the "meta" member of the current document.
     *
     * The method returns an array of non-standard meta information about the document. If
     * this array is empty, the member won't appear in the response.
     */
    public function getMeta(): array
    {
        return [];
    }

    /**
     * Provides information about the "links" member of the current document.
     *
     * The method returns a new Links object if you want to provide linkage data
     * for the document or null if the section should be omitted from the response.
     */
    public function getLinks(): ?DocumentLinks
    {
        return DocumentLinks::createWithoutBaseUri()
            ->setPagination("/books", $this->object, $this->request->getUri()->getQuery());
    }
}
