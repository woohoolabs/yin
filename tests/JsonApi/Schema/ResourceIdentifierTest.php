<?php
namespace WoohooLabsTest\Yin\JsonApi\Schema;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Schema\ResourceIdentifier;

class ResourceIdentifierTest extends PHPUnit_Framework_TestCase
{
    public function testFromArray()
    {
        $type = "user";
        $id = "1";

        $resourceIdentifierArray = [
            "type" => $type,
            "id" => $id
        ];
        $resourceIdentifier = $this->createResourceIdentifier()->setType($type)->setId($id);
        $this->assertEquals($resourceIdentifier, ResourceIdentifier::fromArray($resourceIdentifierArray));
    }

    public function testGetType()
    {
        $type = "book";

        $link = $this->createResourceIdentifier()->setType($type);
        $this->assertEquals($type, $link->getType());
    }

    public function testGetId()
    {
        $id = "123456789";

        $link = $this->createResourceIdentifier()->setId($id);
        $this->assertEquals($id, $link->getId());
    }

    private function createResourceIdentifier()
    {
        return new ResourceIdentifier();
    }
}
