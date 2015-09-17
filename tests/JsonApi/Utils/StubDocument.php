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
