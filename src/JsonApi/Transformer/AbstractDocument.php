<?php
namespace WoohooLabs\Yin\JsonApi\Transformer;

abstract class AbstractDocument
{
    /**
     * @return array
     */
    public function getExtensions()
    {
        return [];
    }

    /**
     * @return array
     */
    public function getSupportedExtensions()
    {
        return [];
    }

    /**
     * @return \WoohooLabs\Yin\JsonApi\Schema\JsonApi|null
     */
    abstract public function getJsonApi();

    /**
     * @return array
     */
    abstract public function getMeta();

    /**
     * @return \WoohooLabs\Yin\JsonApi\Schema\Links|null
     */
    abstract public function getLinks();

    /**
     * @return array
     */
    protected function transformBaseContent()
    {
        $content = [];

        $this->transformJsonApiContent($content);
        $this->transformMetaContent($content);
        $this->transformLinksContent($content);

        return $content;
    }

    /**
     * @param array $content
     */
    protected function transformJsonApiContent(&$content)
    {
        $jsonApi = $this->getJsonApi();
        if ($jsonApi !== null) {
            $content["jsonApi"] = $jsonApi->transform();
        }
    }

    /**
     * @param array $content
     */
    protected function transformMetaContent(&$content)
    {
        $meta = $this->getMeta();
        if (empty($meta) === false) {
            $content["meta"] = $meta;
        }
    }

    /**
     * @param array $content
     */
    protected function transformLinksContent(&$content)
    {
        $links = $this->getLinks();
        if ($links !== null) {
            $content["links"] = $links->transform();
        }
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
