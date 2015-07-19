<?php
namespace WoohooLabs\Yin\Examples\JsonApi\Document;

use WoohooLabs\Yin\Examples\JsonApi\Resource\BookResourceTransformer;
use WoohooLabs\Yin\JsonApi\Schema\Link;
use WoohooLabs\Yin\JsonApi\Schema\Links;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractSingleResourceDocument;

class BookDocument extends AbstractSingleResourceDocument
{
    /**
     * @param \WoohooLabs\Yin\Examples\JsonApi\Resource\BookResourceTransformer $transformer
     */
    public function __construct(BookResourceTransformer $transformer)
    {
        parent::__construct($transformer);
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
        return new Links(
            [
                "self" => new Link("http://example.com/api/books/" . $this->transformer->getId($this->resource))
            ]
        );
    }
}
