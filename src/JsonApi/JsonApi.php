<?php
namespace WoohooLabs\Yin\JsonApi;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Exception\InclusionNotSupported;
use WoohooLabs\Yin\JsonApi\Exception\SortingNotSupported;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Response\CreateResponse;
use WoohooLabs\Yin\JsonApi\Response\DeleteResponse;
use WoohooLabs\Yin\JsonApi\Response\FetchRelationshipResponse;
use WoohooLabs\Yin\JsonApi\Response\FetchResponse;
use WoohooLabs\Yin\JsonApi\Response\UpdateRelationshipResponse;
use WoohooLabs\Yin\JsonApi\Response\UpdateResponse;

class JsonApi
{
    /**
     * @var \WoohooLabs\Yin\JsonApi\Request\RequestInterface
     */
    private $request;

    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    private $response;

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
     * Disables inclusion of related resources.
     *
     * If the current request asks for inclusion of related resources, it throws an InclusionNotSupported exception.
     *
     * @throws \WoohooLabs\Yin\JsonApi\Exception\InclusionNotSupported
     */
    public function disableIncludes()
    {
        if ($this->request->getQueryParam("include") !== null) {
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
        if ($this->request->getQueryParam("sort") !== null) {
            throw new SortingNotSupported();
        }
    }

    /**
     * @return \WoohooLabs\Yin\JsonApi\Response\CreateResponse
     */
    public function createResponse()
    {
        return new CreateResponse($this->request, $this->response);
    }

    /**
     * @return \WoohooLabs\Yin\JsonApi\Response\DeleteResponse
     */
    public function deleteResponse()
    {
        return new DeleteResponse($this->request, $this->response);
    }

    /**
     * @return \WoohooLabs\Yin\JsonApi\Response\FetchResponse
     */
    public function fetchResponse()
    {
        return new FetchResponse($this->request, $this->response);
    }

    /**
     * @param string $relationshipName
     * @return \WoohooLabs\Yin\JsonApi\Response\FetchRelationshipResponse
     */
    public function fetchRelationshipResponse($relationshipName)
    {
        return new FetchRelationshipResponse($this->request, $this->response, $relationshipName);
    }

    /**
     * @param string $relationshipName
     * @return \WoohooLabs\Yin\JsonApi\Response\UpdateRelationshipResponse
     */
    public function updateRelationshipResponse($relationshipName)
    {
        return new UpdateRelationshipResponse($this->request, $this->response, $relationshipName);
    }

    /**
     * @return \WoohooLabs\Yin\JsonApi\Response\UpdateResponse
     */
    public function updateResponse()
    {
        return new UpdateResponse($this->request, $this->response);
    }
}
