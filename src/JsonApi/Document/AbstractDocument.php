<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Document;

use WoohooLabs\Yin\JsonApi\Schema\JsonApiObject;
use WoohooLabs\Yin\JsonApi\Schema\Links;

abstract class AbstractDocument
{
    /**
     * Provides information about the "jsonapi" member of the current document.
     *
     * The method returns a new JsonApiObject schema object if this member should be present or null
     * if it should be omitted from the response.
     */
    abstract public function getJsonApi(): ?JsonApiObject;

    /**
     * Provides information about the "meta" member of the current document.
     *
     * The method returns an array of non-standard meta information about the document. If
     * this array is empty, the member won't appear in the response.
     */
    abstract public function getMeta(): array;

    /**
     * Provides information about the "links" member of the current document.
     *
     * The method returns a new Links schema object if you want to provide linkage data
     * for the document or null if the member should be omitted from the response.
     */
    abstract public function getLinks(): ?Links;

    protected function transformBaseContent(array $additionalMeta = []): array
    {
        $content = [];

        $this->transformJsonApiContent($content);
        $this->transformMetaContent($content, $additionalMeta);
        $this->transformLinksContent($content);

        return $content;
    }

    protected function transformJsonApiContent(array &$content): void
    {
        $jsonApi = $this->getJsonApi();
        if ($jsonApi !== null) {
            $content["jsonapi"] = $jsonApi->transform();
        }
    }

    protected function transformMetaContent(array &$content, array $additionalMeta = []): void
    {
        $meta = array_merge($this->getMeta(), $additionalMeta);
        if (empty($meta) === false) {
            $content["meta"] = $meta;
        }
    }

    protected function transformLinksContent(array &$content): void
    {
        $links = $this->getLinks();
        if ($links !== null) {
            $content["links"] = $links->transform();
        }
    }
}
