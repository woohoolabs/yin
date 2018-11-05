<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema\Relationship;

use WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface;
use WoohooLabs\Yin\JsonApi\Schema\Link\RelationshipLinks;
use WoohooLabs\Yin\JsonApi\Schema\Resource\ResourceInterface;
use WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformation;
use WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformer;

class ToOneRelationship extends AbstractRelationship
{
    public function __construct(
        array $meta = [],
        ?RelationshipLinks $links = null,
        array $data = [],
        ?ResourceInterface $resource = null
    ) {
        parent::__construct($meta, $links, $data, $resource);
    }

    /**
     * @internal
     */
    protected function transformData(
        ResourceTransformation $transformation,
        ResourceTransformer $resourceTransformer,
        DataInterface $data,
        array $defaultRelationships
    ): ?array{
        $object = $this->getData();
        if ($object === null || $this->resource === null) {
            return null;
        }

        return $this->transformResourceIdentifier($transformation, $resourceTransformer, $data, $object, $defaultRelationships);
    }
}
