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
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractCompoundDocument $document
     * @param mixed $resource
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function created(AbstractCompoundDocument $document, $resource)
    {
        $response = self::getDocumentResourceResponse($this->request, $this->response, $document, $resource, 201);

        $links = $document->getLinks();
        if ($links !== null && $links->getSelf() !== null) {
            $response = $response->withHeader("location", $links->getSelf()->getHref());
        }

        return $response;
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public function accepted()
    {
        return $this->response->withStatus(202);
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public function noContent()
    {
        return $this->response->withStatus(204);
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractErrorDocument $document
     * @param array $errors
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public function forbidden(AbstractErrorDocument $document, array $errors = [])
    {
        return $this->getErrorResponse($this->response, $document, $errors, 403);
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractErrorDocument $document
     * @param array $errors
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public function conflict(AbstractErrorDocument $document, array $errors = [])
    {
        return $this->getErrorResponse($this->response, $document, $errors, 409);
    }
}
