<?php
namespace WoohooLabs\Yin\JsonApi\Document;

abstract class AbstractDocument
{
    /**
     * Lists all those extensions by which the response is formatted.
     *
     * @return array
     */
    public function getExtensions()
    {
        return [];
    }

    /**
     * Lists all those extensions which are supported by the endpoint utilizing the current document.
     *
     * @return array
     */
    public function getSupportedExtensions()
    {
        return [];
    }

    /**
     * Provides information about the "jsonApi" section of the current document.
     *
     * The method returns a new JsonApi schema object if this section should be present or null
     * if it should be omitted from the response.
     *
     * @return \WoohooLabs\Yin\JsonApi\Schema\JsonApi|null
     */
    abstract public function getJsonApi();

    /**
     * Provides information about the "meta" section of the current document.
     *
     * The method returns an array of non-standard meta information about the document. If
     * this array is empty, the section won't appear in the response.
     *
     * @return array
     */
    abstract public function getMeta();

    /**
     * Provides information about the "links" section of the current document.
     *
     * The method returns a new Links schema object if you want to provide linkage data
     * for the document or null if the section should be omitted from the response.
     *
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
            $contentType .= '; ext="' . implode(",", $extensions) . '"';
        }

        $supportedExtensions = $this->getSupportedExtensions();
        if (empty($supportedExtensions) === false) {
            $contentType .= '; supported-ext="' . implode(",", $supportedExtensions) . '"';
        }

        return $contentType;
    }
}
