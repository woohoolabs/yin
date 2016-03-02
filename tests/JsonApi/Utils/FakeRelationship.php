<?php
namespace WoohooLabsTest\Yin\JsonApi\Utils;

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
         return $this->retrieveData();
    }
}
