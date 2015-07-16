<?php
namespace WoohooLabs\Yin\Examples\JsonApi\Document;

use WoohooLabs\Yin\Examples\JsonApi\Resource\UserResourceTransformer;
use WoohooLabs\Yin\JsonApi\Request\Criteria;
use WoohooLabs\Yin\JsonApi\Schema\Link;
use WoohooLabs\Yin\JsonApi\Schema\Links;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractCollectionDocument;

class UsersDocument extends AbstractCollectionDocument
{
    /**
     * @var UserResourceTransformer
     */
    protected $userTransformer;

    /**
     * @param UserResourceTransformer $bookTransformer
     */
    public function __construct(UserResourceTransformer $bookTransformer)
    {
        $this->userTransformer = $bookTransformer;
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
                "self" => new Link("http://example.com/api/users")
            ]
        );
    }

    /**
     * @param Criteria $criteria
     */
    protected function setContent(Criteria $criteria)
    {
        $this->data = [];

        foreach ($this->resource as $item) {
            $this->data[] = $this->userTransformer->transformToResource($item, $criteria, $this->included);
        }
    }
}
