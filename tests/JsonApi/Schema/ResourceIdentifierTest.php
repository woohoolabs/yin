<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Schema;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;
use WoohooLabs\Yin\JsonApi\Exception\ResourceIdentifierIdMissing;
use WoohooLabs\Yin\JsonApi\Exception\ResourceIdentifierTypeMissing;
use WoohooLabs\Yin\JsonApi\Schema\ResourceIdentifier;

class ResourceIdentifierTest extends TestCase
{
    /**
     * @test
     */
    public function fromMissingTypeArray()
    {
        $resourceIdentifierArray = ["id" => "1"];

        $this->expectException(ResourceIdentifierTypeMissing::class);
        ResourceIdentifier::fromArray($resourceIdentifierArray, new DefaultExceptionFactory());
    }

    /**
     * @test
     */
    public function fromMissingIdArray()
    {
        $resourceIdentifierArray = ["type" => "user"];

        $this->expectException(ResourceIdentifierIdMissing::class);
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
