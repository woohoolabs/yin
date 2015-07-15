<?php
namespace WoohooLabs\Yin\JsonApi\Transformer;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Request\Criteria;

abstract class AbstractDocument implements DocumentTransformerInterface
{
    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;

    /**
     * @var int
     */
    protected $statusCode;

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * @return array
     */
    protected function getExtensions()
    {
        return [];
    }

    /**
     * @return array
     */
    protected function getSupportedExtensions()
    {
        return [];
    }

    /**
     * @return \WoohooLabs\Yin\JsonApi\Schema\JsonApi|null
     */
    abstract protected function getJsonApi();

    /**
     * @return array
     */
    abstract protected function getMeta();

    /**
     * @return \WoohooLabs\Yin\JsonApi\Schema\Links
     */
    abstract protected function getLinks();

    /**
     * @param int $statusCode
     * @param \WoohooLabs\Yin\JsonApi\Request\Criteria
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getResponse($statusCode, Criteria $criteria)
    {
        $response = $this->response;

        $response->getBody()->write(json_encode($this->transformContent($criteria)));
        $response = $response->withStatus($statusCode);
        $response = $response->withAddedHeader("Content-Type", $this->getContentType());

        return $response;
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\Criteria $criteria
     * @return array
     */
    protected function transformContent(Criteria $criteria)
    {
        $content = [];

        // JsonApi
        $jsonApi = $this->getJsonApi();
        if ($jsonApi !== null) {
            $content["jsonApi"] = $jsonApi->transform();
        }

        // Meta
        $meta = $this->getMeta();
        if (empty($meta) === false) {
            $content["meta"] = $meta;
        }

        // Links
        $links = $this->getLinks();
        if ($links !== null) {
            $content["links"] = $links->transform();
        }

        return $content;
    }

    /**
     * @return string
     */
    protected function getContentType()
    {
        $contentType = "application/vnd.api+json";

        $extensions = $this->getExtensions();
        if (empty($extensions) === false) {
            $contentType .= '; ext="' . implode(";", $extensions) . '"';
        }

        $supportedExtensions = $this->getSupportedExtensions();
        if (empty($supportedExtensions) === false) {
            $contentType .= '; supported-ext="' . implode(";", $supportedExtensions) . '"';
        }

        return $contentType;
    }
}
