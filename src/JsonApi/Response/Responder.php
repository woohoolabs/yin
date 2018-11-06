<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Response;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Schema\Document\AbstractErrorDocument;
use WoohooLabs\Yin\JsonApi\Schema\Document\AbstractResourceDocument;
use WoohooLabs\Yin\JsonApi\Schema\Document\ErrorDocumentInterface;
use WoohooLabs\Yin\JsonApi\Schema\Document\ResourceDocumentInterface;
use WoohooLabs\Yin\JsonApi\Schema\Error\Error;
use WoohooLabs\Yin\JsonApi\Schema\Link\DocumentLinks;
use WoohooLabs\Yin\JsonApi\Serializer\SerializerInterface;
use WoohooLabs\Yin\JsonApi\Transformer\DocumentTransformer;

class Responder extends AbstractResponder
{
    public static function create(
        RequestInterface $request,
        ResponseInterface $response,
        DocumentTransformer $documentTransformer,
        ExceptionFactoryInterface $exceptionFactory,
        SerializerInterface $serializer
    ): Responder {
        return new Responder($request, $response, $documentTransformer, $exceptionFactory, $serializer);
    }

    /**
     * Returns a "200 Ok" response, containing a document in the body with the resource.
     *
     * @param mixed $object
     */
    public function ok(ResourceDocumentInterface $document, $object, array $additionalMeta = []): ResponseInterface
    {
        return $this->getResponse($document, $object, 200, $additionalMeta);
    }

    /**
     * Returns a "200 Ok" response, containing a document in the body with the resource metadata.
     *
     * @param mixed $object
     */
    public function okWithMeta(ResourceDocumentInterface $document, $object, array $additionalMeta = []): ResponseInterface
    {
        return $this->getMetaResponse($document, $object, 200, $additionalMeta);
    }

    /**
     * Returns a "200 Ok" response, containing a document in the body with the relationship. You can also
     * pass additional meta information for the document in the $additionalMeta argument.
     *
     * @param mixed $object
     */
    public function okWithRelationship(
        string $relationshipName,
        AbstractResourceDocument $document,
        $object,
        array $additionalMeta = []
    ): ResponseInterface {
        return $this->getRelationshipResponse(
            $relationshipName,
            $document,
            $object,
            200,
            $additionalMeta
        );
    }

    /**
     * Returns a "201 Created" response, containing a document in the body with the newly created resource. You can also
     * pass additional meta information for the document in the $additionalMeta argument.
     *
     * @param mixed $object
     */
    public function created(
        AbstractResourceDocument $document,
        $object,
        array $additionalMeta = []
    ): ResponseInterface {
        $response = $this->getResponse($document, $object, 201, $additionalMeta);

        $links = $document->getLinks();
        if ($links instanceof DocumentLinks && $links->getSelf() !== null) {
            $response = $response->withHeader("location", $links->getSelf()->getHref());
        }

        return $response;
    }

    /**
     * Returns a "201 Created" response, containing a document in the body with the newly created resource metadata.
     * You can also pass additional meta information for the document in the $additionalMeta argument.
     *
     * @param mixed $object
     */
    public function createdWithMeta(
        AbstractResourceDocument $document,
        $object,
        array $additionalMeta = []
    ): ResponseInterface {
        $response = $this->getMetaResponse($document, $object, 201, $additionalMeta);

        $links = $document->getLinks();
        if ($links instanceof DocumentLinks && $links->getSelf() !== null) {
            $response = $response->withHeader("location", $links->getSelf()->getHref());
        }

        return $response;
    }

    /**
     * Returns a "200 Ok" response, containing a document in the body with the relationship. You can also
     * pass additional meta information for the document in the $additionalMeta argument.
     *
     * @param mixed $object
     */
    public function createdWithRelationship(
        string $relationshipName,
        AbstractResourceDocument $document,
        $object,
        array $additionalMeta = []
    ): ResponseInterface {
        return $this->getRelationshipResponse(
            $relationshipName,
            $document,
            $object,
            201,
            $additionalMeta
        );
    }

    /**
     * Returns a "202 Accepted" response.
     */
    public function accepted(): ResponseInterface
    {
        return $this->response->withStatus(202);
    }

    /**
     * Returns a "204 No Content" response.
     */
    public function noContent(): ResponseInterface
    {
        return $this->response->withStatus(204);
    }

    /**
     * Returns a "403 Forbidden" response, containing a document in the body with the errors. You can also pass
     * additional meta information for the error document in the $additionalMeta argument.
     *
     * @param Error[] $errors
     */
    public function forbidden(
        AbstractErrorDocument $document,
        array $additionalMeta = []
    ): ResponseInterface {
        return $this->getErrorResponse($document, 403, $additionalMeta);
    }

    /**
     * Returns a "404 Not Found" response, containing a document in the body with the errors. You can also pass
     * additional meta information for the error document in the $additionalMeta argument.
     *
     * @param Error[] $errors
     */
    public function notFound(
        AbstractErrorDocument $document,
        array $additionalMeta = []
    ): ResponseInterface {
        return $this->getErrorResponse($document, 404, $additionalMeta);
    }

    /**
     * Returns a "409 Conflict" response, containing a document in the body with the errors. You can also pass
     * additional meta information for the error document in the $additionalMeta argument.
     *
     * @param Error[] $errors
     */
    public function conflict(
        AbstractErrorDocument $document,
        array $additionalMeta = []
    ): ResponseInterface {
        return $this->getErrorResponse($document, 409, $additionalMeta);
    }

    /**
     * Returns a successful response with the given status code.
     */
    public function genericSuccess(int $statusCode): ResponseInterface
    {
        return $this->response->withStatus($statusCode);
    }

    /**
     * Returns an error response, containing a document in the body with the errors. You can also pass additional
     * meta information to the document in the $additionalMeta argument.
     *
     * @param Error[] $errors
     */
    public function genericError(
        ErrorDocumentInterface $document,
        ?int $statusCode = null,
        array $additionalMeta = []
    ): ResponseInterface {
        return $this->getErrorResponse($document, $statusCode, $additionalMeta);
    }
}
