<?php
namespace WoohooLabsTest\Yin\JsonApi\Hydrator\Relationship;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToManyRelationship;
use WoohooLabs\Yin\JsonApi\Schema\ResourceIdentifier;

class ToManyRelationshipTest extends PHPUnit_Framework_TestCase
{
    public function testGetResourceIdentifiers()
    {
        $resourceIdentifier1 = (new ResourceIdentifier())->setType("user")->setId("1");
        $resourceIdentifier2 = (new ResourceIdentifier())->setType("user")->setId("2");

        $relationship = $this->createRelationship()
            ->addResourceIdentifier($resourceIdentifier1)
            ->addResourceIdentifier($resourceIdentifier2);
        $this->assertEquals([$resourceIdentifier1, $resourceIdentifier2], $relationship->getResourceIdentifiers());
    }

    public function testGetResourceIdentifierTypes()
    {
        $type = "user";
        $resourceIdentifier1 = (new ResourceIdentifier())->setType($type)->setId("1");
        $resourceIdentifier2 = (new ResourceIdentifier())->setType($type)->setId("2");

        $relationship = $this->createRelationship()
            ->addResourceIdentifier($resourceIdentifier1)
            ->addResourceIdentifier($resourceIdentifier2);
        $this->assertEquals([$type, $type], $relationship->getResourceIdentifierTypes());
    }

    public function testGetResourceIdentifierIds()
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

    private function createRelationship()
    {
        return new ToManyRelationship();
    }
}
