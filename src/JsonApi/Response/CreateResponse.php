<?php
namespace WoohooLabs\Yin\JsonApi\Response;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractCompoundDocument;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractErrorDocument;

class CreateResponse extends AbstractResponse
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
     * Returns a "201 Created" response, containing a document in the body with the newly created resource.
     *
     * According to the JSON API specification, this response is applicable in the following conditions:
     * "If a POST request did not include a Client-Generated ID and the requested resource has been created
     * successfully, the server MUST return a 201 Created status code."
     *
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractCompoundDocument $document
     * @param mixed $domainObject
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function created(AbstractCompoundDocument $document, $domainObject)
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
     * According to the JSON API specification, this response is applicable in the following conditions:
     * "If a request to create a resource has been accepted for processing, but the processing has not been
     * completed by the time the server responds, the server MUST return a 202 Accepted status code."
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
     * "If A POST request did include a Client-Generated ID and the requested resource
     * has been created successfully, the server MUST return either a 201 Created status
     * code and response document (as described above) or a 204 No Content status code
     * with no response document."
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
     * "A server MAY return 403 Forbidden in response to an unsupported request to create a resource."
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
     * Returns a "409 Conflict" response, containing a document in the body with the errors.
     *
     * According to the JSON API specification, this response is applicable in the following conditions:
     * "A server MUST return 409 Conflict when processing a POST request to create a resource with a
     * client-generated ID that already exists. A server MUST return 409 Conflict when processing a
     * POST request in which the resource object's type is not among the type(s) that constitute the
     * collection represented by the endpoint."
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
