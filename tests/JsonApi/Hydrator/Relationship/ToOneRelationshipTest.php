<?php
namespace WoohooLabsTest\Yin\JsonApi\Hydrator\Relationship;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToOneRelationship;
use WoohooLabs\Yin\JsonApi\Schema\ResourceIdentifier;

class ToOneRelationshipTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function getResourceIdentifierWhenSetInConstructor()
    {
        $resourceIdentifier = (new ResourceIdentifier())->setType("user")->setId("1");

        $relationship = $this->createRelationship($resourceIdentifier);
        $this->assertEquals($resourceIdentifier, $relationship->getResourceIdentifier());
    }

    /**
     * @test
     */
    public function getResourceIdentifier()
    {
        $resourceIdentifier = (new ResourceIdentifier())->setType("user")->setId("1");

        $relationship = $this->createRelationship()->setResourceIdentifier($resourceIdentifier);
        $this->assertEquals($resourceIdentifier, $relationship->getResourceIdentifier());
    }

    private function createRelationship(ResourceIdentifier $resourceIdentifier = null)
    {
        return new ToOneRelationship($resourceIdentifier);
    }
}
