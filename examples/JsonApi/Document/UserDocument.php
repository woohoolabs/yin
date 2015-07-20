<?php
namespace WoohooLabs\Yin\Examples\JsonApi\Document;

use WoohooLabs\Yin\Examples\JsonApi\Resource\UserResourceTransformer;
use WoohooLabs\Yin\JsonApi\Schema\Link;
use WoohooLabs\Yin\JsonApi\Schema\Links;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractSingleResourceDocument;

class UserDocument extends AbstractSingleResourceDocument
{
    /**
     * @param \WoohooLabs\Yin\Examples\JsonApi\Resource\UserResourceTransformer $transformer
     */
    public function __construct(UserResourceTransformer $transformer)
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
                "self" => new Link("http://example.com/api/users/" . $this->transformer->getId($this->resource))
            ]
        );
    }
}
