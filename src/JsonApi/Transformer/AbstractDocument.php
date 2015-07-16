<?php
namespace WoohooLabs\Yin\JsonApi\Transformer;

abstract class AbstractDocument
{
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
     * @return \WoohooLabs\Yin\JsonApi\Schema\Links|null
     */
    abstract protected function getLinks();

    /**
     * @return array
     */
    protected function transformBaseContent()
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
