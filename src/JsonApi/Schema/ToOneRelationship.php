<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

use WoohooLabs\Yin\JsonApi\Request\Request;
use WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformerInterface;

class ToOneRelationship extends AbstractRelationship
{
    /**
     * @return $this
     */
    public static function create() {
        return new self();
    }

    /**
     * @param array $meta
     * @return $this
     */
    public static function createWithMeta(array $meta) {
        return new self($meta);
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Schema\Links $links
     * @return $this
     */
    public static function createWithLinks(Links $links) {
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

    /**
     * @param array $meta
     * @param \WoohooLabs\Yin\JsonApi\Schema\Links $links
     * @param array $data
     * @param \WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformerInterface $resourceTransformer
     */
    public function __construct(
        array $meta = [],
        Links $links = null,
        array $data = [],
        ResourceTransformerInterface $resourceTransformer = null
    ) {
        parent::__construct($data, $resourceTransformer);
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\Request $request
     * @param \WoohooLabs\Yin\JsonApi\Schema\Included $included
     * @param string $baseRelationshipPath
     * @param string $relationshipName
     * @return array
     */
    protected function transformData(Request $request, Included $included, $baseRelationshipPath, $relationshipName)
    {
        if ($this->data === null || $this->resourceTransformer === null) {
            return null;
        }

        return $this->transformResource(
            $this->data,
            $request,
            $included,
            $baseRelationshipPath,
            $relationshipName
        );
    }
}
