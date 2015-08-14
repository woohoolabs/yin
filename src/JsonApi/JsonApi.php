<?php
namespace WoohooLabs\Yin\JsonApi;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Request\RelationshipRequest;
use WoohooLabs\Yin\JsonApi\Request\Request;
use WoohooLabs\Yin\JsonApi\Response\CreateResponse;
use WoohooLabs\Yin\JsonApi\Response\DeleteResponse;
use WoohooLabs\Yin\JsonApi\Response\FetchRelationshipResponse;
use WoohooLabs\Yin\JsonApi\Response\FetchResponse;
use WoohooLabs\Yin\JsonApi\Response\UpdateRelationshipResponse;
use WoohooLabs\Yin\JsonApi\Response\UpdateResponse;

class JsonApi
{
    /**
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    private $request;

    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    private $response;

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     */
    public function __construct(ServerRequestInterface $request, ResponseInterface $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @return \WoohooLabs\Yin\JsonApi\Response\CreateResponse
     */
    public function createResponse()
    {
        return new CreateResponse(Request::fromServerRequest($this->request), $this->response);
    }

    /**
     * @return \WoohooLabs\Yin\JsonApi\Response\DeleteResponse
     */
    public function DeleteResponse()
    {
        return new DeleteResponse(Request::fromServerRequest($this->request), $this->response);
    }

    /**
     * @return \WoohooLabs\Yin\JsonApi\Response\FetchResponse
     */
    public function fetchResponse()
    {
        return new FetchResponse(Request::fromServerRequest($this->request), $this->response);
    }

    /**
     * @param string $resourceType
     * @param string $relationshipName
     * @return \WoohooLabs\Yin\JsonApi\Response\FetchRelationshipResponse
     */
    public function fetchRelationshipResponse($resourceType, $relationshipName)
    {
        return new FetchRelationshipResponse(
            new RelationshipRequest(Request::fromServerRequest($this->request), $resourceType, $relationshipName),
            $this->response,
            $relationshipName
        );
    }

    /**
     * @param string $resourceType
     * @param string $relationshipName
     * @return \WoohooLabs\Yin\JsonApi\Response\UpdateRelationshipResponse
     */
    public function updateRelationshipResponse($resourceType, $relationshipName)
    {
        return new UpdateRelationshipResponse(
            new RelationshipRequest(Request::fromServerRequest($this->request), $resourceType, $relationshipName),
            $this->response,
            $relationshipName
        );
    }

    /**
     * @return \WoohooLabs\Yin\JsonApi\Response\UpdateResponse
     */
    public function updateResponse()
    {
        return new UpdateResponse(Request::fromServerRequest($this->request), $this->response);
    }

    /**
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }
}
