<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Double;

use WoohooLabs\Yin\JsonApi\Schema\Links;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractResourceTransformer;

class StubResourceTransformer extends AbstractResourceTransformer
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var array
     */
    protected $meta;

    /**
     * @var \WoohooLabs\Yin\JsonApi\Schema\Links
     */
    protected $links;

    /**
     * @var array
     */
    protected $attributes;

    /**
     * @var array
     */
    protected $defaultRelationships;

    /**
     * @var array
     */
    protected $relationships;

    /**
     * @param string $type
     * @param string $id
     * @param array $meta
     * @param \WoohooLabs\Yin\JsonApi\Schema\Links|null $links
     * @param array $attributes
     * @param array $defaultRelationships
     * @param array $relationships
     */
    public function __construct(
        $type = "",
        $id = "",
        array $meta = [],
        Links $links = null,
        array $attributes = [],
        array $defaultRelationships = [],
        array $relationships = []
    ) {
        $this->type = $type;
        $this->id = $id;
        $this->meta = $meta;
        $this->links = $links;
        $this->attributes = $attributes;
        $this->defaultRelationships = $defaultRelationships;
        $this->relationships = $relationships;
    }

    /**
     * @inheritDoc
     */
    public function getType($domainObject)
    {
        return $this->type;
    }

    /**
     * @inheritDoc
     */
    public function getId($domainObject)
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function getMeta($domainObject)
    {
        return $this->meta;
    }

    /**
     * @inheritDoc
     */
    public function getLinks($domainObject)
    {
        return $this->links;
    }

    /**
     * @inheritDoc
     */
    public function getAttributes($domainObject)
    {
        return $this->attributes;
    }

    /**
     * @inheritDoc
     */
    public function getDefaultIncludedRelationships($domainObject)
    {
        return $this->defaultRelationships;
    }

    /**
     * @inheritDoc
     */
    public function getRelationships($domainObject)
    {
        return $this->relationships;
    }
}
