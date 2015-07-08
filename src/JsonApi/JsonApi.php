<?php
namespace WoohooLabs\Yin\JsonApi;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use WoohooLabs\Yin\JsonApi\Schema\Error;

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

    public function orchestrate(callable $extract, callable $perform, $transformer)
    {
        if ($this->negotiate()) {
            $action = $extract($this->request);
            $resource = $perform($action);
            $this->transform();
            $this->respond();
        }
    }

    protected function negotiate()
    {
        $success = false;
        $contentType = $this->request->getHeader("Content-Type");
        $accept = $this->request->getHeader("Accept");

        if (strpos($contentType, "application/vnd.api+json") !== false && strpos($contentType, ";") !== false) {
            $this->response = $this->response->withStatus(415);
        } elseif(strpos($accept, "application/vnd.api+json") !== false && strpos($accept, ";") !== false) {
            $this->response = $this->response->withStatus(406);
        } else {
            $success = true;
        }

        return $success;
    }

    /**
     * Transforms the response content.
     */
    public function transform()
    {

    }

    /**
     * Manipulates the response.
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function respond()
    {
        $this->response = $this->response->withAddedHeader("Content-Type", "vnd.api+json");

        return $this->response;
    }

    public function error(Error $error)
    {
        $this->response = $this->response
            ->withStatus($error->getStatus())
            ->getBody()->write($error->serialize());

    }
}
