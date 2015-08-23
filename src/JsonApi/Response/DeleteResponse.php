<?php
namespace WoohooLabs\Yin\JsonApi\Response;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractCompoundDocument;

class DeleteResponse extends AbstractResponse
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
     * Returns a "200 Ok" response, containing a document in the body with the resource meta data.
     *
     * According to the JSON API specification, this response is applicable in the following conditions:
     * "A server MUST return a 200 OK status code if a deletion request is successful and the server responds
     * with only top-level meta data."
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
     * Returns a "202 Accepted" response.
     *
     * According to the JSON API specification, this response is applicable in the following conditions:
     * "If a deletion request has been accepted for processing, but the processing has not been completed
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
     * "A server MUST return a 204 No Content status code if a deletion request is successful and no content
     * is returned."
     *
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public function noContent()
    {
        return $this->response->withStatus(204);
    }
}
