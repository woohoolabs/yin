<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Double;

use WoohooLabs\Yin\JsonApi\Document\AbstractErrorDocument;
use WoohooLabs\Yin\JsonApi\Schema\JsonApiObject;
use WoohooLabs\Yin\JsonApi\Schema\Links;

class StubErrorDocument extends AbstractErrorDocument
{
    /**
     * @var JsonApiObject|null
     */
    protected $jsonApi;

    /**
     * @var array
     */
    protected $meta;

    /**
     * @var Links|null
     */
    protected $links;

    public function __construct(JsonApiObject $jsonApi = null, array $meta = [], Links $links = null)
    {
        $this->jsonApi = $jsonApi;
        $this->meta = $meta;
        $this->links = $links;
    }

    public function getJsonApi()
    {
        return $this->jsonApi;
    }

    public function getMeta(): array
    {
        return $this->meta;
    }

    public function getLinks()
    {
        return $this->links;
    }
}
