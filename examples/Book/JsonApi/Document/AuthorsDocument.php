<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Examples\Book\JsonApi\Document;

use WoohooLabs\Yin\Examples\Book\JsonApi\Resource\AuthorResourceTransformer;
use WoohooLabs\Yin\JsonApi\Document\AbstractCollectionDocument;
use WoohooLabs\Yin\JsonApi\Schema\JsonApiObject;
use WoohooLabs\Yin\JsonApi\Schema\Link\Link;
use WoohooLabs\Yin\JsonApi\Schema\Link\Links;

class AuthorsDocument extends AbstractCollectionDocument
{
    /**
     * @var int
     */
    protected $bookId;

    public function __construct(AuthorResourceTransformer $transformer, int $bookId)
    {
        parent::__construct($transformer);
        $this->bookId = $bookId;
    }

    /**
     * Provides information about the "jsonapi" member of the current document.
     *
     * The method returns a new JsonApiObject schema object if this member should be present or null
     * if it should be omitted from the response.
     */
    public function getJsonApi(): ?JsonApiObject
    {
        return new JsonApiObject("1.0");
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
     * The method returns a new Links schema object if you want to provide linkage data
     * for the document or null if the section should be omitted from the response.
     */
    public function getLinks(): ?Links
    {
        return Links::createWithoutBaseUri(
            [
                "self" => new Link("/?path=/books/" . $this->bookId . "/authors")
            ]
        );
    }
}
