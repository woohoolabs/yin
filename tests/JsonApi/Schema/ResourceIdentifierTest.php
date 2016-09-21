<?php
namespace WoohooLabsTest\Yin\JsonApi\Schema;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;
use WoohooLabs\Yin\JsonApi\Schema\ResourceIdentifier;

class ResourceIdentifierTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException \WoohooLabs\Yin\JsonApi\Exception\ResourceIdentifierTypeMissing
     */
    public function fromMissingTypeArray()
    {
        $resourceIdentifierArray = ["id" => "1"];

        ResourceIdentifier::fromArray($resourceIdentifierArray, new DefaultExceptionFactory());
    }

    /**
     * @test
     * @expectedException \WoohooLabs\Yin\JsonApi\Exception\ResourceIdentifierIdMissing
     */
    public function fromMissingIdArray()
    {
        $resourceIdentifierArray = ["type" => "user"];

        ResourceIdentifier::fromArray($resourceIdentifierArray, new DefaultExceptionFactory());
    }

    /**
     * @test
     */
    public function fromArray()
    {
        $type = "user";
        $id = "1";

        $resourceIdentifierArray = [
            "type" => $type,
            "id" => $id
        ];

        $this->assertEquals(
            $this->createResourceIdentifier()->setType($type)->setId($id),
            ResourceIdentifier::fromArray($resourceIdentifierArray, new DefaultExceptionFactory())
        );
    }

    /**
     * @test
     */
    public function fromArrayWithMeta()
    {
        $type = "user";
        $id = "1";
        $meta = ["abc" => "def"];

        $resourceIdentifierArray = [
            "type" => $type,
            "id" => $id,
            "meta" => $meta
        ];
        $resourceIdentifier = $this->createResourceIdentifier()->setType($type)->setId($id)->setMeta($meta);
        $this->assertEquals(
            $resourceIdentifier,
            ResourceIdentifier::fromArray($resourceIdentifierArray, new DefaultExceptionFactory())
        );
    }

    /**
     * @test
     */
    public function getType()
    {
        $type = "book";

        $link = $this->createResourceIdentifier()->setType($type);
        $this->assertEquals($type, $link->getType());
    }

    /**
     * @test
     */
    public function getId()
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
