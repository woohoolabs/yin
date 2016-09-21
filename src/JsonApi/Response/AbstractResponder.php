<?php
namespace WoohooLabs\Yin\JsonApi\Response;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Document\AbstractErrorDocument;
use WoohooLabs\Yin\JsonApi\Document\AbstractSuccessfulDocument;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Serializer\SerializerInterface;

abstract class AbstractResponder
{
    /**
     * @var \WoohooLabs\Yin\JsonApi\Request\RequestInterface
     */
    protected $request;

    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;

    /**
     * @var \WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface $exceptionFactory
     */
    protected $exceptionFactory;

    /**
     * @var \WoohooLabs\Yin\JsonApi\Serializer\SerializerInterface
     */
    protected $serializer;

    public function __construct(
        RequestInterface $request,
        ResponseInterface $response,
        ExceptionFactoryInterface $exceptionFactory,
        SerializerInterface $serializer
    ) {
        $this->request = $request;
        $this->response = $response;
        $this->exceptionFactory = $exceptionFactory;
        $this->serializer = $serializer;
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Document\AbstractSuccessfulDocument $document
     * @param mixed $domainObject
     * @param int $statusCode
     * @param array $additionalMeta
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function getDocumentResourceResponse(
        AbstractSuccessfulDocument $document,
        $domainObject,
        $statusCode,
        array $additionalMeta = []
    ) {
        return $document->getResponse(
            $this->request,
            $this->response,
            $this->exceptionFactory,
            $this->serializer,
            $domainObject,
            $statusCode,
            $additionalMeta
        );
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Document\AbstractSuccessfulDocument $document
     * @param mixed $domainObject
     * @param int $statusCode
     * @param array $additionalMeta
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function getDocumentMetaResponse(
        AbstractSuccessfulDocument $document,
        $domainObject,
        $statusCode,
        array $additionalMeta = []
    ) {
        return $document->getMetaResponse(
            $this->request,
            $this->response,
            $this->exceptionFactory,
            $this->serializer,
            $domainObject,
            $statusCode,
            $additionalMeta
        );
    }

    /**
     * @param string $relationshipName
     * @param \WoohooLabs\Yin\JsonApi\Document\AbstractSuccessfulDocument $document
     * @param mixed $domainObject
     * @param int $statusCode
     * @param array $additionalMeta
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function getDocumentRelationshipResponse(
        $relationshipName,
        AbstractSuccessfulDocument $document,
        $domainObject,
        $statusCode,
        array $additionalMeta = []
    ) {
        return $document->getRelationshipResponse(
            $relationshipName,
            $this->request,
            $this->response,
            $this->exceptionFactory,
            $this->serializer,
            $domainObject,
            $statusCode,
            $additionalMeta
        );
    }

    /**
     * @param string $relationshipName
     * @param \WoohooLabs\Yin\JsonApi\Document\AbstractSuccessfulDocument $document
     * @param mixed $domainObject
     * @param int $statusCode
     * @param array $additionalMeta
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function getDocumentRelationshipMetaResponse(
        $relationshipName,
        AbstractSuccessfulDocument $document,
        $domainObject,
        $statusCode,
        array $additionalMeta = []
    ) {
        return $document->getRelationshipResponse(
            $relationshipName,
            $this->request,
            $this->response,
            $this->exceptionFactory,
            $this->serializer,
            $domainObject,
            $statusCode,
            $additionalMeta
        );
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \WoohooLabs\Yin\JsonApi\Document\AbstractErrorDocument $document
     * @param \WoohooLabs\Yin\JsonApi\Schema\Error[] $errors
     * @param int $statusCode
     * @param array $additionalMeta
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    protected function getErrorResponse(
        ResponseInterface $response,
        AbstractErrorDocument $document,
        array $errors,
        $statusCode,
        array $additionalMeta = []
    ) {
        foreach ($errors as $error) {
            $document->addError($error);
        }

        return $document->getResponse($this->serializer, $response, $statusCode, $additionalMeta);
    }
}
