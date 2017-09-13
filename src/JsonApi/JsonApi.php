<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Exception\InclusionUnsupported;
use WoohooLabs\Yin\JsonApi\Exception\JsonApiExceptionInterface;
use WoohooLabs\Yin\JsonApi\Exception\SortingUnsupported;
use WoohooLabs\Yin\JsonApi\Hydrator\HydratorInterface;
use WoohooLabs\Yin\JsonApi\Hydrator\UpdateRelationshipHydratorInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Response\Responder;
use WoohooLabs\Yin\JsonApi\Serializer\JsonSerializer;
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
        ?ExceptionFactoryInterface $exceptionFactory = null,
        ?SerializerInterface $serializer = null
    ) {
        $this->request = $request;
        $this->response = $response;
        $this->exceptionFactory = $exceptionFactory ?? new DefaultExceptionFactory();
        $this->serializer = $serializer ?? new JsonSerializer();
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

    public function setResponse(ResponseInterface $response): void
    {
        $this->response = $response;
    }

    public function getExceptionFactory(): ExceptionFactoryInterface
    {
        return $this->exceptionFactory;
    }

    public function setExceptionFactory($exceptionFactory): void
    {
        $this->exceptionFactory = $exceptionFactory;
    }

    public function respond(): Responder
    {
        return new Responder($this->request, $this->response, $this->exceptionFactory, $this->serializer);
    }

    /**
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
     * @throws InclusionUnsupported|JsonApiExceptionInterface
     */
    public function disableIncludes(): void
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
     * @throws SortingUnsupported|JsonApiExceptionInterface
     */
    public function disableSorting(): void
    {
        if ($this->request->getQueryParam("sort") !== null) {
            throw $this->exceptionFactory->createSortingUnsupportedException($this->request);
        }
    }
}
