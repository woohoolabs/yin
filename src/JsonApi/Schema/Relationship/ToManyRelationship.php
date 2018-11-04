<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema\Relationship;

use WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface;
use WoohooLabs\Yin\JsonApi\Schema\Link\RelationshipLinks;
use WoohooLabs\Yin\JsonApi\Schema\Resource\ResourceInterface;
use WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformation;
use WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformer;

class ToManyRelationship extends AbstractRelationship
{
    public function __construct(
        array $meta = [],
        ?RelationshipLinks $links = null,
        array $data = [],
        ?ResourceInterface $resource = null
    ) {
        parent::__construct($meta, $links, $data, $resource);
    }

    protected function transformData(
        ResourceTransformation $transformation,
        ResourceTransformer $resourceTransformer,
        DataInterface $data,
        array $defaultRelationships
    ): ?array {
        /** @var iterable $object */
        $object = $this->getData();
        if (empty($object)) {
            return [];
        }

        $result = [];
        foreach ($object as $item) {
            $resourceIdentifier = $this->transformResourceIdentifier($transformation, $resourceTransformer, $data, $item, $defaultRelationships);

            if ($resourceIdentifier !== null) {
                $result[] = $resourceIdentifier;
            }
        }

        return $result;
    }
}
