<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Hydrator\HydratorInterface;
use WoohooLabs\Yin\JsonApi\Hydrator\UpdateRelationshipHydratorInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Response\RelationshipResponder;
use WoohooLabs\Yin\JsonApi\Response\Responder;
use WoohooLabs\Yin\JsonApi\Serializer\DefaultSerializer;
use WoohooLabs\Yin\JsonApi\Serializer\SerializerInterface;

class JsonApi
{
    /**
     * @var RequestInterface
     */
    public $request;

    /**
     * @var ResponseInterface
     */
    public $response;

    /**
     * @var ExceptionFactoryInterface
     */
    protected $exceptionFactory;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    public function __construct(
        RequestInterface $request,
        ResponseInterface $response,
        ExceptionFactoryInterface $exceptionFactory = null,
        SerializerInterface $serializer = null
    ) {
        $this->request = $request;
        $this->response = $response;
        $this->exceptionFactory = $exceptionFactory ? $exceptionFactory : new DefaultExceptionFactory();
        $this->serializer = $serializer ? $serializer : new DefaultSerializer();
    }

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    public function setRequest(RequestInterface $request)
    {
        $this->request = $request;
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    public function setResponse(ResponseInterface $response)
    {
        $this->response = $response;
    }

    public function getExceptionFactory(): ExceptionFactoryInterface
    {
        return $this->exceptionFactory;
    }

    public function setExceptionFactory($exceptionFactory)
    {
        $this->exceptionFactory = $exceptionFactory;
    }

    public function respond(): Responder
    {
        return new Responder($this->request, $this->response, $this->exceptionFactory, $this->serializer);
    }

    public function respondWithRelationship(string $relationship): RelationshipResponder
    {
        return new RelationshipResponder(
            $this->request,
            $this->response,
            $this->exceptionFactory,
            $this->serializer,
            $relationship
        );
    }

    /**
     * @param HydratorInterface $hydrator
     * @param mixed $domainObject
     * @return mixed
     */
    public function hydrate(HydratorInterface $hydrator, $domainObject)
    {
        return $hydrator->hydrate($this->request, $this->exceptionFactory, $domainObject);
    }

    /**
     * @param mixed $domainObject
     * @return mixed
     */
    public function hydrateRelationship(
        string $relationship,
        UpdateRelationshipHydratorInterface $hydrator,
        $domainObject
    ) {
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
