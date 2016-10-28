<?php
namespace WoohooLabs\Yin\Tests\JsonApi\Double;

use WoohooLabs\Yin\JsonApi\Schema\Relationship\AbstractRelationship;
use WoohooLabs\Yin\JsonApi\Transformer\Transformation;

class FakeRelationship extends AbstractRelationship
{
    /**
     * @inheritDoc
     */
    protected function transformData(
        Transformation $transformation,
        $relationshipName,
        array $defaultRelationships
    ) {
        return [];
    }

    public function getRetrieveData()
    {
        return $this->retrieveData();
    }

    public function isOmitWhenNotIncluded()
    {
        return $this->omitDataWhenNotIncluded;
    }
}
