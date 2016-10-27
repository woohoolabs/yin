<?php
namespace WoohooLabsTest\Yin\JsonApi\Double;

use WoohooLabs\Yin\JsonApi\Document\AbstractErrorDocument;
use WoohooLabs\Yin\JsonApi\Schema\JsonApi;
use WoohooLabs\Yin\JsonApi\Schema\Links;

class StubErrorDocument extends AbstractErrorDocument
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
     * @inheritDoc
     */
    public function __construct(JsonApi $jsonApi = null, array $meta = [], Links $links = null)
    {
        $this->jsonApi = $jsonApi;
        $this->meta = $meta;
        $this->links = $links;
    }

    /**
     * @inheritDoc
     */
    public function getJsonApi()
    {
        return $this->jsonApi;
    }

    /**
     * @inheritDoc
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * @inheritDoc
     */
    public function getLinks()
    {
        return $this->links;
    }
}
