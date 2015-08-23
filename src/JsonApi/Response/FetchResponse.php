<?php
namespace WoohooLabs\Yin\JsonApi\Response;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractCompoundDocument;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractErrorDocument;

class FetchResponse extends AbstractResponse
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
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractCompoundDocument $document
     * @param mixed $resource
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function ok(AbstractCompoundDocument $document, $resource)
    {
        return $this->getDocumentResourceResponse($this->request, $this->response, $document, $resource, 200);
    }

    /**
     * Returns a "200 Ok" response, containing a document in the body with the resource meta data.
     *
     * According to the JSON API specification, this response is applicable in the following conditions:
     * "A server MUST respond to a successful request to fetch an individual resource or resource
     * collection with a 200 OK response."
     *
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractCompoundDocument $document
     * @param mixed $resource
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function okWithMeta(AbstractCompoundDocument $document, $resource)
    {
        return $this->getDocumentMetaResponse($this->request, $this->response, $document, $resource, 200);
    }

    /**
     * Returns a "404 Not Found" response, containing a document in the body with the errors.
     *
     * According to the JSON API specification, this response is applicable in the following conditions:
     * "A server MUST respond with 404 Not Found when processing a request to fetch a single resource that
     * does not exist, except when the request warrants a 200 OK response with null as the primary
     * data (as described above)."
     *
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractErrorDocument $document
     * @param array $errors
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public function notFound(AbstractErrorDocument $document, array $errors = [])
    {
        return $this->getErrorResponse($this->response, $document, $errors, 404);
    }
}
