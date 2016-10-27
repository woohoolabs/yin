<?php
namespace WoohooLabsTest\Yin\JsonApi\Hydrator\Relationship;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToManyRelationship;
use WoohooLabs\Yin\JsonApi\Schema\ResourceIdentifier;

class ToManyRelationshipTest extends TestCase
{
    /**
     * @test
     */
    public function getResourceIdentifiers()
    {
        $resourceIdentifier1 = (new ResourceIdentifier())->setType("user")->setId("1");
        $resourceIdentifier2 = (new ResourceIdentifier())->setType("user")->setId("2");

        $relationship = $this->createRelationship()
            ->addResourceIdentifier($resourceIdentifier1)
            ->addResourceIdentifier($resourceIdentifier2);
        $this->assertEquals([$resourceIdentifier1, $resourceIdentifier2], $relationship->getResourceIdentifiers());
    }

    /**
     * @test
     */
    public function getResourceIdentifierTypes()
    {
        $type = "user";
        $resourceIdentifier1 = (new ResourceIdentifier())->setType($type)->setId("1");
        $resourceIdentifier2 = (new ResourceIdentifier())->setType($type)->setId("2");

        $relationship = $this->createRelationship()
            ->addResourceIdentifier($resourceIdentifier1)
            ->addResourceIdentifier($resourceIdentifier2);
        $this->assertEquals([$type, $type], $relationship->getResourceIdentifierTypes());
    }

    /**
     * @test
     */
    public function getResourceIdentifierIds()
    {
        $id1 = "1";
        $resourceIdentifier1 = (new ResourceIdentifier())->setType("user")->setId($id1);
        $id2 = "2";
        $resourceIdentifier2 = (new ResourceIdentifier())->setType("user")->setId($id2);

        $relationship = $this->createRelationship()
            ->addResourceIdentifier($resourceIdentifier1)
            ->addResourceIdentifier($resourceIdentifier2);
        $this->assertEquals([$id1, $id2], $relationship->getResourceIdentifierIds());
    }

    /**
     * @test
     */
    public function isEmptyIsFalse()
    {
        $relationship = $this->createRelationship()
            ->addResourceIdentifier((new ResourceIdentifier()));

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

    private function createRelationship()
    {
        return new ToManyRelationship();
    }
}
