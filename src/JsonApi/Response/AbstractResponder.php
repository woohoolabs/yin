<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Response;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Schema\Document\AbstractErrorDocument;
use WoohooLabs\Yin\JsonApi\Schema\Document\AbstractSuccessfulDocument;
use WoohooLabs\Yin\JsonApi\Schema\Document\SuccessfulDocumentInterface;
use WoohooLabs\Yin\JsonApi\Schema\Error\Error;
use WoohooLabs\Yin\JsonApi\Serializer\SerializerInterface;
use WoohooLabs\Yin\JsonApi\Transformer\DocumentTransformer;
use WoohooLabs\Yin\JsonApi\Transformer\SuccessfulDocumentTransformation;

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
     * @var DocumentTransformer
     */
    protected $documentTransformer;

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
        DocumentTransformer $documentTransformer,
        ExceptionFactoryInterface $exceptionFactory,
        SerializerInterface $serializer
    ) {
        $this->request = $request;
        $this->response = $response;
        $this->documentTransformer = $documentTransformer;
        $this->exceptionFactory = $exceptionFactory;
        $this->serializer = $serializer;
    }

    /**
     * @param mixed $object
     */
    protected function getResponse(
        SuccessfulDocumentInterface $document,
        $object,
        int $statusCode,
        array $additionalMeta = []
    ): ResponseInterface {
        $transformation = new SuccessfulDocumentTransformation(
            $document,
            $object,
            $this->request,
            "",
            "",
            $additionalMeta,
            $this->exceptionFactory
        );

        $transformation = $this->documentTransformer->transformFullDocument($transformation);

        return $this->serializer->serialize($this->response, $statusCode, $transformation->result);
    }

    /**
     * @param mixed $object
     */
    protected function getMetaResponse(
        AbstractSuccessfulDocument $document,
        $object,
        int $statusCode,
        array $additionalMeta = []
    ): ResponseInterface {
        $transformation = new SuccessfulDocumentTransformation(
            $document,
            $object,
            $this->request,
            "",
            "",
            $additionalMeta,
            $this->exceptionFactory
        );

        $transformation = $this->documentTransformer->transformMetaDocument($transformation);

        return $this->serializer->serialize($this->response, $statusCode, $transformation->result);
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
