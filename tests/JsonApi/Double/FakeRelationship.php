<?php

declare(strict_types=1);

namespace Devleand\Yin\Tests\JsonApi\Double;

use Devleand\Yin\JsonApi\Schema\Data\DataInterface;
use Devleand\Yin\JsonApi\Schema\Relationship\AbstractRelationship;
use Devleand\Yin\JsonApi\Transformer\ResourceTransformation;
use Devleand\Yin\JsonApi\Transformer\ResourceTransformer;

class FakeRelationship extends AbstractRelationship
{
    protected function transformData(
        ResourceTransformation $transformation,
        ResourceTransformer $resourceTransformer,
        DataInterface $data,
        array $defaultRelationships
    ): ?array {
        return [];
    }

    /**
     * @return mixed
     */
    public function getRelationshipData()
    {
        return $this->getData();
    }

    public function isOmitDataWhenNotIncluded(): bool
    {
        return $this->omitDataWhenNotIncluded;
    }
}
