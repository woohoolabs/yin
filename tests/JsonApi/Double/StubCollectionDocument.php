<?php

declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Double;

use WoohooLabs\Yin\JsonApi\Schema\Document\AbstractCollectionDocument;
use WoohooLabs\Yin\JsonApi\Schema\JsonApiObject;
use WoohooLabs\Yin\JsonApi\Schema\Link\DocumentLinks;
use WoohooLabs\Yin\JsonApi\Schema\Resource\ResourceInterface;

class StubCollectionDocument extends AbstractCollectionDocument
{
    /** @var JsonApiObject|null */
    protected $jsonApi;

    /** @var array */
    protected $meta;

    /** @var DocumentLinks|null */
    protected $links;

    public function __construct(
        ?JsonApiObject $jsonApi = null,
        array $meta = [],
        ?DocumentLinks $links = null,
        ?ResourceInterface $resource = null,
        iterable $object = []
    ) {
        parent::__construct($resource ?? new StubResource());
        $this->jsonApi = $jsonApi;
        $this->meta = $meta;
        $this->links = $links;
        $this->object = $object;
    }

    public function getJsonApi(): ?JsonApiObject
    {
        return $this->jsonApi;
    }

    public function getMeta(): array
    {
        return $this->meta;
    }

    public function getLinks(): ?DocumentLinks
    {
        return $this->links;
    }

    public function getHasItems(): bool
    {
        return $this->hasItems();
    }
}
