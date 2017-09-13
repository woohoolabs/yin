<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Response;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Document\AbstractErrorDocument;
use WoohooLabs\Yin\JsonApi\Document\AbstractSuccessfulDocument;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Schema\Error;
use WoohooLabs\Yin\JsonApi\Serializer\SerializerInterface;

abstract class AbstractResponder
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @var ExceptionFactoryInterface $exceptionFactory
     */
    protected $exceptionFactory;

    /**
     * @var SerializerInterface
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
     * @param mixed $domainObject
     */
    protected function getResponse(
        AbstractSuccessfulDocument $document,
        $domainObject,
        int $statusCode,
        array $additionalMeta = []
    ): ResponseInterface {
        $content = $document->getContent(
            $this->request,
            $this->exceptionFactory,
            $domainObject,
            $additionalMeta
        );

        return $this->serializer->serialize($this->response, $statusCode, $content);
    }

    /**
     * @param mixed $domainObject
     */
    protected function getMetaResponse(
        AbstractSuccessfulDocument $document,
        $domainObject,
        int $statusCode,
        array $additionalMeta = []
    ): ResponseInterface {
        $content = $document->getMetaContent($domainObject, $additionalMeta);

        return $this->serializer->serialize($this->response, $statusCode, $content);
    }

    /**
     * @param mixed $domainObject
     */
    protected function getRelationshipResponse(
        string $relationshipName,
        AbstractSuccessfulDocument $document,
        $domainObject,
        int $statusCode,
        array $additionalMeta = []
    ): ResponseInterface {
        $content = $document->getRelationshipContent(
            $relationshipName,
            $this->request,
            $this->exceptionFactory,
            $domainObject,
            $additionalMeta
        );

        return $this->serializer->serialize($this->response, $statusCode, $content);
    }

    /**
     * @param Error[] $errors
     */
    protected function getErrorResponse(
        AbstractErrorDocument $document,
        array $errors = [],
        ?int $statusCode = null,
        array $additionalMeta = []
    ): ResponseInterface {
        foreach ($errors as $error) {
            $document->addError($error);
        }

        $content = $document->getContent($additionalMeta);
        $responseCode = $document->getResponseCode($statusCode);

        return $this->serializer->serialize($this->response, $responseCode, $content);
    }
}
