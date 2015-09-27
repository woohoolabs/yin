<?php
namespace WoohooLabs\Yin\JsonApi;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Exception\InclusionNotSupported;
use WoohooLabs\Yin\JsonApi\Exception\SortingNotSupported;
use WoohooLabs\Yin\JsonApi\Hydrator\HydratorInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Response\RelationshipResponder;
use WoohooLabs\Yin\JsonApi\Response\Responder;

class JsonApi
{
    /**
     * @var \WoohooLabs\Yin\JsonApi\Request\RequestInterface
     */
    public $request;

    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    public $response;

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     */
    public function __construct(RequestInterface $request, ResponseInterface $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * Returns the current JSON API request.
     *
     * @return \WoohooLabs\Yin\JsonApi\Request\RequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     */
    public function setRequest(RequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     */
    public function setResponse(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * @return \WoohooLabs\Yin\JsonApi\Response\Responder
     */
    public function respond()
    {
        return new Responder($this->request, $this->response);
    }

    /**
     * @param string $relationship
     * @return \WoohooLabs\Yin\JsonApi\Response\RelationshipResponder
     */
    public function respondWithRelationship($relationship)
    {
        return new RelationshipResponder($this->request, $this->response, $relationship);
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Hydrator\HydratorInterface $hydrator
     * @param $domainObject
     * @return mixed
     */
    public function hydrate(HydratorInterface $hydrator, $domainObject)
    {
        return $hydrator->hydrate($this->request, $domainObject);
    }

    /**
     * Disables inclusion of related resources.
     *
     * If the current request asks for inclusion of related resources, it throws an InclusionNotSupported exception.
     *
     * @throws \WoohooLabs\Yin\JsonApi\Exception\InclusionNotSupported
     */
    public function disableIncludes()
    {
        if ($this->getRequest()->getQueryParam("include") !== null) {
            throw new InclusionNotSupported();
        }
    }

    /**
     * Disables sorting.
     *
     * If the current request contains sorting criteria, it throws a SortingNotSupported exception.
     *
     * @throws \WoohooLabs\Yin\JsonApi\Exception\SortingNotSupported
     */
    public function disableSorting()
    {
        if ($this->getRequest()->getQueryParam("sort") !== null) {
            throw new SortingNotSupported();
        }
    }
}
