<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema\Relationship;

use WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface;
use WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformation;
use WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformer;

class ToOneRelationship extends AbstractRelationship
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
        $object = $this->getData();
        if ($object === null || $this->resource === null) {
            return null;
        }

        return $this->transformResourceIdentifier($transformation, $resourceTransformer, $data, $object, $defaultRelationships);
    }
}
