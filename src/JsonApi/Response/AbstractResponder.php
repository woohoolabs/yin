<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Response;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Schema\Document\AbstractErrorDocument;
use WoohooLabs\Yin\JsonApi\Schema\Document\AbstractResourceDocument;
use WoohooLabs\Yin\JsonApi\Schema\Document\ResourceDocumentInterface;
use WoohooLabs\Yin\JsonApi\Schema\Error\Error;
use WoohooLabs\Yin\JsonApi\Serializer\SerializerInterface;
use WoohooLabs\Yin\JsonApi\Transformer\DocumentTransformer;
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
        AbstractResourceDocument $document,
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
        AbstractResourceDocument $document,
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
        AbstractErrorDocument $document,
        array $errors = [],
        ?int $statusCode = null,
        array $additionalMeta = []
    ): ResponseInterface {
        foreach ($errors as $error) {
            $document->addError($error);
        }

        $content = $document->getContent($additionalMeta);
        $statusCode = $document->getStatusCode($statusCode);

        return $this->serializer->serialize($this->response, $statusCode, $content);
    }
}
