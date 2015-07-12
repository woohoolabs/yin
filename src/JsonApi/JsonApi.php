<?php
namespace WoohooLabs\Yin\JsonApi;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use WoohooLabs\Yin\JsonApi\Transformer\DocumentTransformerInterface;

class JsonApi
{
    /**
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    private $request;

    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    private $response;

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     */
    public function __construct(ServerRequestInterface $request, ResponseInterface $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function orchestrate(callable $extract, callable $perform, DocumentTransformerInterface $transformer)
    {
        if ($this->negotiate()) {
            $action = $extract($this->request);
            $resource = $perform($action);
            $this->transform($transformer);
        }
    }

    protected function negotiate()
    {
        $success = false;
        $contentType = $this->request->getHeader("Content-Type");
        $accept = $this->request->getHeader("Accept");

        if (strpos($contentType, "application/vnd.api+json") !== false && strpos($contentType, ";") !== false) {
            $this->response = $this->response->withStatus(415);
        } elseif (strpos($accept, "application/vnd.api+json") !== false && strpos($accept, ";") !== false) {
            $this->response = $this->response->withStatus(406);
        } else {
            $success = true;
        }

        return $success;
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Transformer\DocumentTransformerInterface $document
     */
    public function transform(DocumentTransformerInterface $document)
    {
        $this->response = $document->transformResponse();
    }
}
