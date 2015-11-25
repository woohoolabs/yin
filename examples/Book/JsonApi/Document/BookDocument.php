<?php
namespace WoohooLabs\Yin\Examples\Book\JsonApi\Document;

use WoohooLabs\Yin\Examples\Book\JsonApi\Resource\BookResourceTransformer;
use WoohooLabs\Yin\JsonApi\Schema\JsonApi;
use WoohooLabs\Yin\JsonApi\Schema\Link;
use WoohooLabs\Yin\JsonApi\Schema\Links;
use WoohooLabs\Yin\JsonApi\Document\AbstractSingleResourceDocument;

class BookDocument extends AbstractSingleResourceDocument
{
    /**
     * @var array
     */
    protected $domainObject;

    /**
     * @param \WoohooLabs\Yin\Examples\Book\JsonApi\Resource\BookResourceTransformer $transformer
     */
    public function __construct(BookResourceTransformer $transformer)
    {
        parent::__construct($transformer);
    }

    /**
     * Provides information about the "jsonApi" section of the current document.
     *
     * The method returns a new JsonApi schema object if this section should be present or null
     * if it should be omitted from the response.
     *
     * @return \WoohooLabs\Yin\JsonApi\Schema\JsonApi|null
     */
    public function getJsonApi()
    {
        return new JsonApi("1.0");
    }

    /**
     * Provides information about the "meta" section of the current document.
     *
     * The method returns an array of non-standard meta information about the document. If
     * this array is empty, the section won't appear in the response.
     *
     * @return array
     */
    public function getMeta()
    {
        return [];
    }

    /**
     * Provides information about the "links" section of the current document.
     *
     * The method returns a new Links schema object if you want to provide linkage data
     * for the document or null if the section should be omitted from the response.
     *
     * @return \WoohooLabs\Yin\JsonApi\Schema\Links|null
     */
    public function getLinks()
    {
        return Links::createWithoutBaseUri(
            [
                "self" => new Link("/books/" . $this->getResourceId())
            ]
        );
    }
}
