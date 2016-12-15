<?php
namespace WoohooLabs\Yin\JsonApi\Document;

abstract class AbstractDocument
{
    /**
     * Provides information about the "jsonApi" member of the current document.
     *
     * The method returns a new JsonApi schema object if this member should be present or null
     * if it should be omitted from the response.
     *
     * @return \WoohooLabs\Yin\JsonApi\Schema\JsonApi|null
     */
    abstract public function getJsonApi();

    /**
     * Provides information about the "meta" member of the current document.
     *
     * The method returns an array of non-standard meta information about the document. If
     * this array is empty, the member won't appear in the response.
     *
     * @return array
     */
    abstract public function getMeta();

    /**
     * Provides information about the "links" member of the current document.
     *
     * The method returns a new Links schema object if you want to provide linkage data
     * for the document or null if the section should be omitted from the response.
     *
     * @return \WoohooLabs\Yin\JsonApi\Schema\Links|null
     */
    abstract public function getLinks();

    /**
     * @param array $additionalMeta
     * @return array
     */
    protected function transformBaseContent(array $additionalMeta = [])
    {
        $content = [];

        $this->transformJsonApiContent($content);
        $this->transformMetaContent($content, $additionalMeta);
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
            $content["jsonapi"] = $jsonApi->transform();
        }
    }

    /**
     * @param array $content
     * @param array $additionalMeta
     */
    protected function transformMetaContent(&$content, array $additionalMeta = [])
    {
        $meta = array_merge($this->getMeta(), $additionalMeta);
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
}
