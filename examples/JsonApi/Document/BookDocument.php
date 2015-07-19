<?php
namespace WoohooLabs\Yin\Examples\JsonApi\Document;

use WoohooLabs\Yin\Examples\JsonApi\Resource\BookResourceTransformer;
use WoohooLabs\Yin\JsonApi\Request\Request;
use WoohooLabs\Yin\JsonApi\Schema\CompulsoryLinks;
use WoohooLabs\Yin\JsonApi\Schema\Link;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractSingleResourceDocument;

class BookDocument extends AbstractSingleResourceDocument
{
    /**
     * @var BookResourceTransformer
     */
    protected $bookTransformer;

    /**
     * @param BookResourceTransformer $bookTransformer
     */
    public function __construct(BookResourceTransformer $bookTransformer)
    {
        $this->bookTransformer = $bookTransformer;
    }

    /**
     * @return \WoohooLabs\Yin\JsonApi\Schema\JsonApi|null
     */
    protected function getJsonApi()
    {
        return null;
    }

    /**
     * @return array
     */
    protected function getMeta()
    {
        return [];
    }

    /**
     * @return \WoohooLabs\Yin\JsonApi\Schema\Links|null
     */
    protected function getLinks()
    {
        return new CompulsoryLinks(
            new Link("http://example.com/api/books/" . $this->bookTransformer->getId($this->resource))
        );
    }

    /**
     * @param Request $request
     */
    protected function setContent(Request $request)
    {
        $this->data = $this->bookTransformer->transformToResource($this->resource, $request, $this->included);
    }
}
