<?php

declare(strict_types=1);

namespace Devleand\Yin\JsonApi\Response;

use Psr\Http\Message\ResponseInterface;
use Devleand\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use Devleand\Yin\JsonApi\Request\JsonApiRequestInterface;
use Devleand\Yin\JsonApi\Schema\Document\DocumentInterface;
use Devleand\Yin\JsonApi\Schema\Document\ErrorDocumentInterface;
use Devleand\Yin\JsonApi\Schema\Document\ResourceDocumentInterface;
use Devleand\Yin\JsonApi\Serializer\SerializerInterface;
use Devleand\Yin\JsonApi\Transformer\DocumentTransformer;
use Devleand\Yin\JsonApi\Transformer\ErrorDocumentTransformation;
use Devleand\Yin\JsonApi\Transformer\ResourceDocumentTransformation;

use function implode;

abstract class AbstractResponder
{
    /**
     * @var JsonApiRequestInterface
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
    protected function getResourceResponse(
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
        $response = $this->getResponse($document, $this->response, $statusCode);

        return $this->serializer->serialize($response, $transformation->result);
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
        $response = $this->getResponse($document, $this->response, $statusCode);

        return $this->serializer->serialize($response, $transformation->result);
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
        $response = $this->getResponse($document, $this->response, $statusCode);

        return $this->serializer->serialize($response, $transformation->result);
    }

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
        $response = $this->getResponse($document, $this->response, $document->getStatusCode($statusCode));

        return $this->serializer->serialize($response, $transformation->result);
    }

    protected function getResponse(DocumentInterface $document, ResponseInterface $response, int $statusCode): ResponseInterface
    {
        $response = $response->withStatus($statusCode);
        $response = $this->getResponseWithContentTypeHeader($document, $response);

        return $response;
    }

    protected function getResponseWithContentTypeHeader(DocumentInterface $document, ResponseInterface $response): ResponseInterface
    {
        $links = $document->getLinks();
        if ($links === null) {
            return $response->withHeader("content-type", "application/vnd.api+json");
        }

        $profiles = $links->getProfiles();
        if (empty($profiles)) {
            return $response->withHeader("content-type", "application/vnd.api+json");
        }

        $hrefs = [];
        foreach ($profiles as $profile) {
            $hrefs[] = $profile->getHref();
        }
        $profileLinks = implode(" ", $hrefs);

        return $response->withHeader("content-type", "application/vnd.api+json;profile=\"$profileLinks\"");
    }

    protected function getResponseWithLocationHeader(ResourceDocumentInterface $document, ResponseInterface $response): ResponseInterface
    {
        $links = $document->getLinks();
        if ($links !== null && $links->getSelf() !== null) {
            $response = $response->withHeader("location", $links->getSelf()->getHref());
        }

        return $response;
    }
}
