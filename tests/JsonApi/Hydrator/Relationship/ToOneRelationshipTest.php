<?php
namespace WoohooLabsTest\Yin\JsonApi\Hydrator\Relationship;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToOneRelationship;
use WoohooLabs\Yin\JsonApi\Schema\ResourceIdentifier;

class ToOneRelationshipTest extends TestCase
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
    public function setResourceIdentifier()
    {
        $resourceIdentifier = (new ResourceIdentifier())->setType("user")->setId("1");

        $relationship = $this->createRelationship()->setResourceIdentifier($resourceIdentifier);
        $this->assertEquals($resourceIdentifier, $relationship->getResourceIdentifier());
    }

    /**
     * @test
     */
    public function isEmptyIsFalse()
    {
        $relationship = $this->createRelationship(new ResourceIdentifier());

        $this->assertFalse($relationship->isEmpty());
    }

    /**
     * @test
     */
    public function isEmptyIsTrue()
    {
        $relationship = $this->createRelationship();

        $this->assertTrue($relationship->isEmpty());
    }

    /**
     * @return ToOneRelationship
     */
    private function createRelationship(ResourceIdentifier $resourceIdentifier = null)
    {
        return new ToOneRelationship($resourceIdentifier);
    }
}
