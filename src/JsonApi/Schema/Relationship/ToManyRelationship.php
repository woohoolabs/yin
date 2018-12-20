<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema\Relationship;

use WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface;
use WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformation;
use WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformer;

class ToManyRelationship extends AbstractRelationship
{
    /**
     * @internal
     */
    protected function transformData(
        ResourceTransformation $transformation,
        ResourceTransformer $resourceTransformer,
        DataInterface $data,
        array $defaultRelationships
    ): ?array {
        /** @var iterable $object */
        $object = $this->getData();
        if (empty($object) || $this->resource === null) {
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
