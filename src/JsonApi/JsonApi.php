<?php
namespace WoohooLabs\Yin\JsonApi;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Request\RelationshipRequest;
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
     * @return \WoohooLabs\Yin\JsonApi\Request\RequestInterface
     */
    public function getRequest()
    {
        return $this->request;
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
     * @param string $resourceType
     * @param string $relationshipName
     * @return \WoohooLabs\Yin\JsonApi\Response\FetchRelationshipResponse
     */
    public function fetchRelationshipResponse($resourceType, $relationshipName)
    {
        return new FetchRelationshipResponse(
            new RelationshipRequest($this->request, $resourceType, $relationshipName),
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
            new RelationshipRequest($this->request, $resourceType, $relationshipName),
            $this->response,
            $relationshipName
        );
    }

    /**
     * @return \WoohooLabs\Yin\JsonApi\Response\UpdateResponse
     */
    public function updateResponse()
    {
        return new UpdateResponse($this->request, $this->response);
    }
}
