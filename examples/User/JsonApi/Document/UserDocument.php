<?php
namespace WoohooLabs\Yin\Examples\User\JsonApi\Document;

use WoohooLabs\Yin\Examples\User\JsonApi\Resource\UserResourceTransformer;
use WoohooLabs\Yin\JsonApi\Schema\Link;
use WoohooLabs\Yin\JsonApi\Schema\Links;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractSingleResourceDocument;

class UserDocument extends AbstractSingleResourceDocument
{
    /**
     * @param \WoohooLabs\Yin\Examples\User\JsonApi\Resource\UserResourceTransformer $transformer
     */
    public function __construct(UserResourceTransformer $transformer)
    {
        parent::__construct($transformer);
    }

    /**
     * @return \WoohooLabs\Yin\JsonApi\Schema\JsonApi|null
     */
    public function getJsonApi()
    {
        return null;
    }

    /**
     * @return array
     */
    public function getMeta()
    {
        return [];
    }

    /**
     * @return \WoohooLabs\Yin\JsonApi\Schema\Links|null
     */
    public function getLinks()
    {
        return new Links(
            [
                "self" => new Link("http://example.com/api/users/" . $this->transformer->getId($this->domainObject))
            ]
        );
    }
}
