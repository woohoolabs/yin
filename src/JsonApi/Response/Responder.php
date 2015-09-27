<?php
namespace WoohooLabs\Yin\JsonApi\Response;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractErrorDocument;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractSuccessfulDocument;

class Responder extends AbstractResponder
{
    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     */
    public function __construct(RequestInterface $request, ResponseInterface $response)
    {
        parent::__construct($request, $response);
    }

    /**
     * Returns a "200 Ok" response, containing a document in the body with the resource.
     *
     * According to the JSON API specification, this response is applicable in the following conditions:
     * "A server MUST respond to a successful request to fetch an individual resource or resource
     * collection with a 200 OK response."
     *
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractSuccessfulDocument $document
     * @param mixed $domainObject
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function ok(AbstractSuccessfulDocument $document, $domainObject)
    {
        return $this->getDocumentResourceResponse($this->request, $this->response, $document, $domainObject, 200);
    }

    /**
     * Returns a "200 Ok" response, containing a document in the body with the resource meta data.
     *
     * According to the JSON API specification, this response is applicable in the following conditions:
     * "A server MUST return a 200 OK status code if a deletion request is successful and the server responds
     * with only top-level meta data."
     *
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractSuccessfulDocument $document
     * @param mixed $domainObject
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function okWithMeta(AbstractSuccessfulDocument $document, $domainObject)
    {
        return $this->getDocumentMetaResponse($this->response, $document, $domainObject, 200);
    }

    /**
     * Returns a "201 Created" response, containing a document in the body with the newly created resource.
     *
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractSuccessfulDocument $document
     * @param mixed $domainObject
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function created(AbstractSuccessfulDocument $document, $domainObject)
    {
        $response = self::getDocumentResourceResponse($this->request, $this->response, $document, $domainObject, 201);

        $links = $document->getLinks();
        if ($links !== null && $links->getSelf() !== null) {
            $response = $response->withHeader("location", $links->getSelf()->getHref());
        }

        return $response;
    }

    /**
     * Returns a "202 Accepted" response.
     *
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public function accepted()
    {
        return $this->response->withStatus(202);
    }

    /**
     * Returns a "204 No Content" response.
     *
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public function noContent()
    {
        return $this->response->withStatus(204);
    }

    /**
     * Returns a "403 Forbidden" response, containing a document in the body with the errors.
     *
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractErrorDocument $document
     * @param \WoohooLabs\Yin\JsonApi\Schema\Error[] $errors
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public function forbidden(AbstractErrorDocument $document, array $errors = [])
    {
        return $this->getErrorResponse($this->response, $document, $errors, 403);
    }

    /**
     * Returns a "404 Not Found" response, containing a document in the body with the errors.
     *
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractErrorDocument $document
     * @param \WoohooLabs\Yin\JsonApi\Schema\Error[] $errors
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public function notFound(AbstractErrorDocument $document, array $errors = [])
    {
        return $this->getErrorResponse($this->response, $document, $errors, 404);
    }

    /**
     * Returns a "409 Conflict" response, containing a document in the body with the errors.
     *
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractErrorDocument $document
     * @param \WoohooLabs\Yin\JsonApi\Schema\Error[] $errors
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public function conflict(AbstractErrorDocument $document, array $errors = [])
    {
        return $this->getErrorResponse($this->response, $document, $errors, 409);
    }

    /**
     * Returns a successful response with the given status code.
     *
     * @param int $statusCode
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public function genericSuccess($statusCode)
    {
        return $this->response->withStatus($statusCode);
    }

    /**
     * Returns an error response, containing a document in the body with the errors.
     *
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractErrorDocument $document
     * @param \WoohooLabs\Yin\JsonApi\Schema\Error[] $errors
     * @param int|null $statusCode
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public function genericError(AbstractErrorDocument $document, array $errors = [], $statusCode = null)
    {
        return $this->getErrorResponse($this->response, $document, $errors, $statusCode);
    }
}
