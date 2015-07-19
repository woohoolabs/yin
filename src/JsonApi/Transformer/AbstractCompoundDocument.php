<?php
namespace WoohooLabs\Yin\JsonApi\Transformer;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Request\Request;
use WoohooLabs\Yin\JsonApi\Schema\Included;

abstract class AbstractCompoundDocument extends AbstractDocument
{
    /**
     * @var mixed
     */
    protected $resource;

    /**
     * @var mixed
     */
    protected $data;

    /**
     * @var \WoohooLabs\Yin\JsonApi\Schema\Included
     */
    protected $included;

    /**
     * Set the value of the "data" and "included" properties based on the "resource" property.
     *
     * @param \WoohooLabs\Yin\JsonApi\Request\Request $request
     */
    abstract protected function setContent(Request $request);

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param mixed $resource
     * @param \WoohooLabs\Yin\JsonApi\Request\Request $request
     * @param int $responseCode
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getResponse(ResponseInterface $response, $resource, Request $request, $responseCode = 200)
    {
        $this->resource = $resource;
        $this->included = new Included();

        $response->getBody()->write(json_encode($this->transformContent($request)));
        $response = $response->withStatus($responseCode);
        $response = $response->withAddedHeader("Content-Type", $this->getContentType());

        return $response;
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\Request $request
     * @return array
     */
    protected function transformContent(Request $request)
    {
        $content = $this->transformBaseContent();

        // Data
        $this->setContent($request);
        $content["data"] = $this->data;

        // Included
        if ($this->included !== null) {
            $content["included"] = $this->included->transform($this->resource, $request);
        }

        return $content;
    }

    /**
     * @return \WoohooLabs\Yin\JsonApi\Schema\Included
     */
    public function getIncluded()
    {
        return $this->included;
    }
}
