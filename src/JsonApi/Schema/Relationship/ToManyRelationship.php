<?php
namespace WoohooLabs\Yin\JsonApi\Schema\Relationship;

use WoohooLabs\Yin\JsonApi\Schema\Links;
use WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformerInterface;
use WoohooLabs\Yin\JsonApi\Transformer\Transformation;

class ToManyRelationship extends AbstractRelationship
{
    use RelationshipFactoryTrait;

    /**
     * @param array $meta
     * @param \WoohooLabs\Yin\JsonApi\Schema\Links|null $links
     * @param array $data
     * @param \WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformerInterface|null $resourceTransformer
     */
    public function __construct(
        array $meta = [],
        Links $links = null,
        array $data = [],
        ResourceTransformerInterface $resourceTransformer = null
    ) {
        parent::__construct($meta, $links, $data, $resourceTransformer);
    }

    /**
     * @inheritDoc
     */
    protected function transformData(
        Transformation $transformation,
        $relationshipName,
        array $defaultRelationships
    ) {
        $data = $this->retrieveData();
        if (empty($data) || $this->resourceTransformer === null) {
            return null;
        }

        $content = [];
        foreach ($data as $item) {
            $content[] = $this->transformResource($transformation, $item, $relationshipName, $defaultRelationships);
        }

        return $content;
    }
}
