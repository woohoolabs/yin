<?php
namespace Src\JsonApi\Document;

use Psr\Http\Message\ResponseInterface;
use Src\JsonApi\Resource\UserResourceTransformer;
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
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param mixed $resource
     * @param UserResourceTransformer $bookTransformer
     */
    public function __construct(ResponseInterface $response, $resource, UserResourceTransformer $bookTransformer)
    {
        parent::__construct($response, $resource);
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
        return null;
    }

    /**
     * @return \WoohooLabs\Yin\JsonApi\Schema\Links
     */
    protected function getLinks()
    {
        return new Links([
            "self" => new Link("http://example.com/api/users")
        ]);
    }

    /**
     * @param mixed $resource
     * @param Criteria $criteria
     */
    protected function setContent($resource, Criteria $criteria)
    {
        $this->data = $this->userTransformer->transformToResource($resource, $criteria, $this->included);
    }
}
