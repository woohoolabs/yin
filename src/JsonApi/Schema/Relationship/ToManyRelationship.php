<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema\Relationship;

use WoohooLabs\Yin\JsonApi\Schema\Links;
use WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformerInterface;
use WoohooLabs\Yin\JsonApi\Transformer\Transformation;

class ToManyRelationship extends AbstractRelationship
{
    public function __construct(
        array $meta = [],
        ?Links $links = null,
        array $data = [],
        ?ResourceTransformerInterface $resourceTransformer = null
    ) {
        parent::__construct($meta, $links, $data, $resourceTransformer);
    }

    protected function transformData(
        Transformation $transformation,
        string $relationshipName,
        array $defaultRelationships
    ): ?array {
        $data = $this->retrieveData();
        if (empty($data) || $this->resourceTransformer === null) {
            return [];
        }

        $content = [];
        foreach ($data as $item) {
            $content[] = $this->transformResource($transformation, $item, $relationshipName, $defaultRelationships);
        }

        return $content;
    }
}
