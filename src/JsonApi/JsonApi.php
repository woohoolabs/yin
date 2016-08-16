<?php
namespace WoohooLabs\Yin\JsonApi;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Hydrator\HydratorInterface;
use WoohooLabs\Yin\JsonApi\Hydrator\UpdateRelationshipHydratorInterface;
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
     * @var \WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface
     */
    public $exceptionFactory;

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface
     */
    public function __construct(
        RequestInterface $request,
        ResponseInterface $response,
        ExceptionFactoryInterface $exceptionFactory
    ) {
        $this->request = $request;
        $this->response = $response;
        $this->exceptionFactory = $exceptionFactory;
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
     * @return \WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface
     */
    public function getExceptionFactory()
    {
        return $this->exceptionFactory;
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface $exceptionFactory
     */
    public function setExceptionFactory($exceptionFactory)
    {
        $this->exceptionFactory = $exceptionFactory;
    }

    /**
     * @return \WoohooLabs\Yin\JsonApi\Response\Responder
     */
    public function respond()
    {
        return new Responder($this->request, $this->response, $this->exceptionFactory);
    }

    /**
     * @param string $relationship
     * @return \WoohooLabs\Yin\JsonApi\Response\RelationshipResponder
     */
    public function respondWithRelationship($relationship)
    {
        return new RelationshipResponder($this->request, $this->response, $this->exceptionFactory, $relationship);
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Hydrator\HydratorInterface $hydrator
     * @param $domainObject
     * @return mixed
     */
    public function hydrate(HydratorInterface $hydrator, $domainObject)
    {
        return $hydrator->hydrate($this->request, $this->exceptionFactory, $domainObject);
    }

	/**
	 * @param string $relationship
	 * @param \WoohooLabs\Yin\JsonApi\Hydrator\UpdateRelationshipHydratorInterface $hydrator
	 * @param $domainObject
	 * @return mixed
	 */
	public function hydrateRelationship($relationship, UpdateRelationshipHydratorInterface $hydrator, $domainObject)
	{
		return $hydrator->hydrateRelationship($relationship, $this->request, $this->exceptionFactory, $domainObject);
	}

    /**
     * Disables inclusion of related resources.
     *
     * If the current request asks for inclusion of related resources, it throws an InclusionNotSupported exception.
     *
     * @throws \WoohooLabs\Yin\JsonApi\Exception\InclusionUnsupported
     */
    public function disableIncludes()
    {
        if ($this->request->getQueryParam("include") !== null) {
            throw $this->exceptionFactory->createInclusionUnsupportedException($this->request);
        }
    }

    /**
     * Disables sorting.
     *
     * If the current request contains sorting criteria, it throws a SortingNotSupported exception.
     *
     * @throws \WoohooLabs\Yin\JsonApi\Exception\SortingUnsupported
     */
    public function disableSorting()
    {
        if ($this->request->getQueryParam("sort") !== null) {
            throw $this->exceptionFactory->createSortingUnsupportedException($this->request);
        }
    }
}
