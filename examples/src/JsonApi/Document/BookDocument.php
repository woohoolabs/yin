<?php
namespace Src\JsonApi\Document;

use Psr\Http\Message\ResponseInterface;
use Src\JsonApi\Resource\BookResourceTransformer;
use WoohooLabs\Yin\JsonApi\Request\Criteria;
use WoohooLabs\Yin\JsonApi\Schema\Link;
use WoohooLabs\Yin\JsonApi\Schema\Links;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractSingleDocument;

class BookDocument extends AbstractSingleDocument
{
    /**
     * @var BookResourceTransformer
     */
    protected $bookTransformer;

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param mixed $resource
     * @param BookResourceTransformer $bookTransformer
     */
    public function __construct(ResponseInterface $response, $resource, BookResourceTransformer $bookTransformer)
    {
        parent::__construct($response, $resource);
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
        return null;
    }

    /**
     * @return \WoohooLabs\Yin\JsonApi\Schema\Links
     */
    protected function getLinks()
    {
        $self = new Link("books\\" . $this->bookTransformer->getId($this->resource));
        $links = new Links($self);

        return $links;
    }

    /**
     * @param mixed $resource
     * @param Criteria $criteria
     */
    protected function setContent($resource, Criteria $criteria)
    {
        $this->data = $this->bookTransformer->transformToResource($resource, $criteria, $this->included);
    }
}
