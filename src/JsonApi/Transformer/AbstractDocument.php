<?php
namespace WoohooLabs\Yin\JsonApi\Transformer;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Request\Criteria;

abstract class AbstractDocument implements DocumentTransformerInterface
{
    use TransformerTrait;

    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;



    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \WoohooLabs\Yin\JsonApi\Request\Criteria $criteria
     */
    public function __construct(ResponseInterface $response, Criteria $criteria)
    {
        $this->response = $response;
        $this->criteria = $criteria;
    }

    /**
     * @return array
     */
    protected function getExtensions() {
        return [];
    }

    /**
     * @return array
     */
    protected function getSupportedExtensions() {
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
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function transformResponse()
    {
        $response = $this->response;

        $response->withAddedHeader("Content-Type", $this->getContentType());
        $response->getBody()->write(json_encode($this->transformContent()));

        return $response;
    }

    /**
     * @return array
     */
    protected function transformContent()
    {
        $content = [];

        $this->addOptionalTransformedItemToArray($this->criteria, $content, "jsonApi", $this->getJsonApi());
        $this->addOptionalTransformedItemToArray($this->criteria, $content, "meta", $this->getMeta());
        $this->addOptionalTransformedItemToArray($this->criteria, $content, "links", $this->getLinks());

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
