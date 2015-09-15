<?php
namespace WoohooLabsTest\Yin\JsonApi\Utils;

use WoohooLabs\Yin\JsonApi\Schema\JsonApi;
use WoohooLabs\Yin\JsonApi\Schema\Links;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractDocument;

class StubDocument extends AbstractDocument
{
    /**
     * @var \WoohooLabs\Yin\JsonApi\Schema\JsonApi
     */
    protected $jsonApi;

    /**
     * @var array
     */
    protected $meta;

    /**
     * @var \WoohooLabs\Yin\JsonApi\Schema\Links
     */
    protected $links;

    /**
     * @param \WoohooLabs\Yin\JsonApi\Schema\JsonApi|null $jsonApi
     * @param array $meta
     * @param \WoohooLabs\Yin\JsonApi\Schema\Links|null $links
     */
    public function __construct(JsonApi $jsonApi = null, array $meta = [], Links $links = null)
    {
        $this->jsonApi = $jsonApi;
        $this->meta = $meta;
        $this->links = $links;
    }

    /**
     * Provides information about the "jsonApi" section of the current document.
     *
     * The method returns a new JsonApi schema object if this section should be present or null
     * if it should be omitted from the response.
     *
     * @return \WoohooLabs\Yin\JsonApi\Schema\JsonApi|null
     */
    public function getJsonApi()
    {
        return $this->jsonApi;
    }

    /**
     * Provides information about the "meta" section of the current document.
     *
     * The method returns an array of non-standard meta information about the document. If
     * this array is empty, the section won't appear in the response.
     *
     * @return array
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * Provides information about the "links" section of the current document.
     *
     * The method returns a new Links schema object if you want to provide linkage data
     * for the document or null if the section should be omitted from the response.
     *
     * @return \WoohooLabs\Yin\JsonApi\Schema\Links|null
     */
    public function getLinks()
    {
        return $this->links;
    }
}
