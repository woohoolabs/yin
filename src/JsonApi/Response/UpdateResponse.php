<?php
namespace WoohooLabs\Yin\JsonApi\Response;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractCompoundDocument;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractErrorDocument;

class UpdateResponse extends AbstractResponse
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
     * Returns a "200 Ok" response, containing a document in thebody with the resource(s).
     *
     * According to the JSON API specification, this response is applicable in the following conditions:
     * "If a server accepts an update but also changes the resource(s) in ways other than those specified
     * by the request (for example, updating the updated-at attribute or a computed sha), it MUST return
     * a 200 OK response."
     *
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractCompoundDocument $document
     * @param mixed $domainObject
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function ok(AbstractCompoundDocument $document, $domainObject)
    {
        return $this->getDocumentResourceResponse($this->request, $this->response, $document, $domainObject, 200);
    }

    /**
     * Returns a "200 Ok" response, containing a document in the body with the resource meta data.
     *
     * According to the JSON API specification, this response is applicable in the following conditions:
     * "A server MUST return a 200 OK status code if an update is successful, the client's current attributes
     * remain up to date, and the server responds only with top-level meta data. In this case the server
     * MUST NOT include a representation of the updated resource(s)."
     *
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractCompoundDocument $document
     * @param mixed $domainObject
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function okWithMeta(AbstractCompoundDocument $document, $domainObject)
    {
        return $this->getDocumentMetaResponse($this->response, $document, $domainObject, 200);
    }

    /**
     * Returns a "202 Accepted" response.
     *
     * According to the JSON API specification, this response is applicable in the following conditions:
     * "If an update request has been accepted for processing, but the processing has not been completed
     * by the time the server responds, the server MUST return a 202 Accepted status code."
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
     * According to the JSON API specification, this response is applicable in the following conditions:
     * "If an update is successful and the server doesn't update any attributes besides those provided,
     * the server MUST return either a 200 OK status code and response document (as described above) or
     * a 204 No Content status code with no response document."
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
     * According to the JSON API specification, this response is applicable in the following conditions:
     * "A server MUST return 403 Forbidden in response to an unsupported request to update a resource or
     * relationship."
     *
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractErrorDocument $document
     * @param array $errors
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public function forbidden(AbstractErrorDocument $document, array $errors = [])
    {
        return $this->getErrorResponse($this->response, $document, $errors, 403);
    }

    /**
     * Returns a "404 Not Found" response, containing a document in the body with the errors.
     *
     * According to the JSON API specification, this response is applicable in the following conditions:
     * "A server MUST return 404 Not Found when processing a request to modify a resource that does not exist.
     * A server MUST return 404 Not Found when processing a request that references a related resource that
     * does not exist."
     *
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractErrorDocument $document
     * @param array $errors
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public function notFound(AbstractErrorDocument $document, array $errors = [])
    {
        return $this->getErrorResponse($this->response, $document, $errors, 404);
    }

    /**
     * Returns a "409 Conflict" response, containing a document in the body with the errors.
     *
     * According to the JSON API specification, this response is applicable in the following conditions:
     * "A server MAY return 409 Conflict when processing a PATCH request to update a resource if that
     * update would violate other server-enforced constraints (such as a uniqueness constraint on a
     * property other than id). A server MUST return 409 Conflict when processing a PATCH request in
     * which the resource object's type and id do not match the server's endpoint."
     *
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractErrorDocument $document
     * @param array $errors
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public function conflict(AbstractErrorDocument $document, array $errors = [])
    {
        return $this->getErrorResponse($this->response, $document, $errors, 409);
    }
}
