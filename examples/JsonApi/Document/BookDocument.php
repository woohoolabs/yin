<?php
namespace WoohooLabs\Yin\Examples\JsonApi\Document;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\Examples\JsonApi\Resource\BookResourceTransformer;
use WoohooLabs\Yin\JsonApi\Request\Criteria;
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
        return new CompulsoryLinks(
            new Link("http://example.com/api/books/" . $this->bookTransformer->getId($this->resource))
        );
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
