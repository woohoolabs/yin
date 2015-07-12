<?php
namespace WoohooLabs\Yin\JsonApi\Transformer;

use Psr\Http\Message\ResponseInterface;

abstract class AbstractDocument implements DocumentTransformerInterface
{
    use TransformerTrait;

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
     * @param int $statusCode
     */
    public function __construct(ResponseInterface $response, $statusCode)
    {
        $this->response = $response;
        $this->statusCode = $statusCode;
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

        $response->getBody()->write(json_encode($this->transformContent()));
        $response = $response->withStatus($this->statusCode);
        $response = $response->withAddedHeader("Content-Type", $this->getContentType());

        return $response;
    }

    /**
     * @return array
     */
    protected function transformContent()
    {
        $content = [];

        $this->addOptionalSimpleTransformedItemToArray($content, "jsonApi", $this->getJsonApi());
        $this->addOptionalSimpleTransformedItemToArray($content, "meta", $this->getMeta());
        $this->addOptionalSimpleTransformedItemToArray($content, "links", $this->getLinks());

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
