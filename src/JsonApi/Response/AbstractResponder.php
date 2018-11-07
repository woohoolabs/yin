<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Response;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Schema\Document\ErrorDocumentInterface;
use WoohooLabs\Yin\JsonApi\Schema\Document\ResourceDocumentInterface;
use WoohooLabs\Yin\JsonApi\Schema\Error\Error;
use WoohooLabs\Yin\JsonApi\Serializer\SerializerInterface;
use WoohooLabs\Yin\JsonApi\Transformer\DocumentTransformer;
use WoohooLabs\Yin\JsonApi\Transformer\ErrorDocumentTransformation;
use WoohooLabs\Yin\JsonApi\Transformer\ResourceDocumentTransformation;

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

    /**
     * @param mixed $object
     */
    protected function getResponse(
        ResourceDocumentInterface $document,
        $object,
        int $statusCode,
        array $additionalMeta = []
    ): ResponseInterface {
        $transformation = new ResourceDocumentTransformation(
            $document,
            $object,
            $this->request,
            "",
            "",
            $additionalMeta,
            $this->exceptionFactory
        );

        $transformation = $this->documentTransformer->transformResourceDocument($transformation);

        return $this->serializer->serialize($this->response, $statusCode, $transformation->result);
    }

    /**
     * @param mixed $object
     */
    protected function getMetaResponse(
        ResourceDocumentInterface $document,
        $object,
        int $statusCode,
        array $additionalMeta = []
    ): ResponseInterface {
        $transformation = new ResourceDocumentTransformation(
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
     * @param mixed $object
     */
    protected function getRelationshipResponse(
        string $relationshipName,
        ResourceDocumentInterface $document,
        $object,
        int $statusCode,
        array $additionalMeta = []
    ): ResponseInterface {
        $transformation = new ResourceDocumentTransformation(
            $document,
            $object,
            $this->request,
            "",
            $relationshipName,
            $additionalMeta,
            $this->exceptionFactory
        );

        $transformation = $this->documentTransformer->transformRelationshipDocument($transformation);

        return $this->serializer->serialize($this->response, $statusCode, $transformation->result);
    }

    /**
     * @param Error[] $errors
     */
    protected function getErrorResponse(
        ErrorDocumentInterface $document,
        ?int $statusCode = null,
        array $additionalMeta = []
    ): ResponseInterface {
        $transformation = new ErrorDocumentTransformation(
            $document,
            $this->request,
            $additionalMeta,
            $this->exceptionFactory
        );

        $transformation = $this->documentTransformer->transformErrorDocument($transformation);

        return $this->serializer->serialize($this->response, $document->getStatusCode($statusCode), $transformation->result);
    }
}
