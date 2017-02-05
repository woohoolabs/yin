<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema\Relationship;

use WoohooLabs\Yin\JsonApi\Schema\Links;
use WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformerInterface;
use WoohooLabs\Yin\JsonApi\Transformer\Transformation;

class ToOneRelationship extends AbstractRelationship
{
    use RelationshipFactoryTrait;

    public function __construct(
        array $meta = [],
        Links $links = null,
        array $data = [],
        ResourceTransformerInterface $resourceTransformer = null
    ) {
        parent::__construct($meta, $links, $data, $resourceTransformer);
    }

    protected function transformData(
        Transformation $transformation,
        string $relationshipName,
        array $defaultRelationships
    ) {
        $data = $this->retrieveData();
        if (isset($data) === false || $this->resourceTransformer === null) {
            return null;
        }

        return $this->transformResource(
            $transformation,
            $data,
            $relationshipName,
            $defaultRelationships
        );
    }
}
