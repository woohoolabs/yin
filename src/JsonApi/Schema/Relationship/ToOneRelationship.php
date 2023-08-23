<?php

declare(strict_types=1);

namespace Devleand\Yin\JsonApi\Schema\Relationship;

use Devleand\Yin\JsonApi\Schema\Data\DataInterface;
use Devleand\Yin\JsonApi\Transformer\ResourceTransformation;
use Devleand\Yin\JsonApi\Transformer\ResourceTransformer;

class ToOneRelationship extends AbstractRelationship
{
    /**
     * @internal
     * @return array|false|null
     */
    protected function transformData(
        ResourceTransformation $transformation,
        ResourceTransformer $resourceTransformer,
        DataInterface $data,
        array $defaultRelationships
    ) {
        if ($this->resource === null) {
            return false;
        }

        $object = $this->getData();
        if ($object === null) {
            return null;
        }

        return $this->transformResourceIdentifier($transformation, $resourceTransformer, $data, $object, $defaultRelationships);
    }
}
