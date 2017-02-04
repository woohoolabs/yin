<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema\Relationship;

use WoohooLabs\Yin\JsonApi\Schema\Links;
use WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformerInterface;

trait RelationshipFactoryTrait
{
    /**
     * @return $this
     */
    public static function create()
    {
        return new self();
    }

    /**
     * @param array $meta
     * @return $this
     */
    public static function createWithMeta(array $meta)
    {
        return new self($meta);
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Schema\Links $links
     * @return $this
     */
    public static function createWithLinks(Links $links)
    {
        return new self([], $links);
    }

    /**
     * @param array $data
     * @param \WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformerInterface $resourceTransformer
     * @return $this
     */
    public static function createWithData(array $data, ResourceTransformerInterface $resourceTransformer)
    {
        return new self([], null, $data, $resourceTransformer);
    }
}
