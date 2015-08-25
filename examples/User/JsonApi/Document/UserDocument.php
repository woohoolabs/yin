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
     * Provides information about the "jsonApi" section of the current document.
     *
     * The method returns a new JsonApi schema object if this section should be present or null
     * if it should be omitted from the response.
     *
     * @return \WoohooLabs\Yin\JsonApi\Schema\JsonApi|null
     */
    public function getJsonApi()
    {
        return null;
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
     *  Provides information about the "links" section of the current document.
     *
     * The method returns a new Links schema object if you want to provide linkage data
     * for the document or null if the section should be omitted from the response.
     *
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
