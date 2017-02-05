<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Double;

use WoohooLabs\Yin\JsonApi\Schema\Relationship\AbstractRelationship;
use WoohooLabs\Yin\JsonApi\Transformer\Transformation;

class FakeRelationship extends AbstractRelationship
{
    protected function transformData(
        Transformation $transformation,
        string $relationshipName,
        array $defaultRelationships
    ) {
        return [];
    }

    /**
     * @return mixed
     */
    public function getRetrieveData()
    {
        return $this->retrieveData();
    }

    public function isOmitWhenNotIncluded(): bool
    {
        return $this->omitDataWhenNotIncluded;
    }
}
