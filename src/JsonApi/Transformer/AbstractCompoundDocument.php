<?php
namespace WoohooLabs\Yin\JsonApi\Transformer;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
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
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     */
    abstract protected function setContent(RequestInterface $request);

    /**
     * @param string $relationshipName
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @return array
     */
    abstract protected function getRelationshipContent($relationshipName, RequestInterface $request);

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param mixed $resource
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param int $responseCode
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getResponse(ResponseInterface $response, $resource, RequestInterface $request, $responseCode = 200)
    {
        $this->resource = $resource;
        $this->included = new Included();

        $response->getBody()->write(json_encode($this->transformContent($request)));
        $response = $response->withStatus($responseCode);
        $response = $response->withAddedHeader("Content-Type", $this->getContentType());

        return $response;
    }

    /**
     * @param string $relationshipName
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param mixed $resource
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param int $responseCode
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getRelationshipResponse(
        $relationshipName,
        ResponseInterface $response,
        $resource,
        RequestInterface $request,
        $responseCode = 200
    ) {
        $this->resource = $resource;
        $this->included = new Included();

        $response->getBody()->write(json_encode($this->transformRelationshipContent($relationshipName, $request)));
        $response = $response->withStatus($responseCode);
        $response = $response->withAddedHeader("Content-Type", $this->getContentType());

        return $response;
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @return array
     */
    protected function transformContent(RequestInterface $request)
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
     * @param string $relationshipName
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @return array
     */
    protected function transformRelationshipContent($relationshipName, RequestInterface $request)
    {
        return $this->getRelationshipContent($relationshipName, $request);
    }

    /**
     * @return \WoohooLabs\Yin\JsonApi\Schema\Included
     */
    public function getIncluded()
    {
        return $this->included;
    }
}
