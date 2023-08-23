<?php

declare(strict_types=1);

namespace Devleand\Yin\Tests\JsonApi\Schema;

use PHPUnit\Framework\TestCase;
use Devleand\Yin\JsonApi\Exception\DefaultExceptionFactory;
use Devleand\Yin\JsonApi\Exception\ResourceIdentifierIdInvalid;
use Devleand\Yin\JsonApi\Exception\ResourceIdentifierIdMissing;
use Devleand\Yin\JsonApi\Exception\ResourceIdentifierTypeInvalid;
use Devleand\Yin\JsonApi\Exception\ResourceIdentifierTypeMissing;
use Devleand\Yin\JsonApi\Schema\ResourceIdentifier;

class ResourceIdentifierTest extends TestCase
{
    /**
     * @test
     */
    public function fromArrayWithMissingType(): void
    {
        $this->expectException(ResourceIdentifierTypeMissing::class);

        ResourceIdentifier::fromArray(["id" => "1"], new DefaultExceptionFactory());
    }

    /**
     * @test
     */
    public function fromArrayWithNotStringType(): void
    {
        $this->expectException(ResourceIdentifierTypeInvalid::class);

        ResourceIdentifier::fromArray(["type" => 0, "id" => 1], new DefaultExceptionFactory());
    }

    /**
     * @test
     */
    public function fromArrayWithMissingId(): void
    {
        $this->expectException(ResourceIdentifierIdMissing::class);

        ResourceIdentifier::fromArray(["type" => "user"], new DefaultExceptionFactory());
    }

    /**
     * @test
     */
    public function fromArrayWithNotStringId(): void
    {
        $this->expectException(ResourceIdentifierIdInvalid::class);

        ResourceIdentifier::fromArray(["type" => "abc", "id" => 1], new DefaultExceptionFactory());
    }

    /**
     * @test
     */
    public function fromArrayWithZeroTypeAndId(): void
    {
        $resourceIdentifier = $this->createResourceIdentifier()
            ->setType("0")
            ->setId("0");

        $resourceIdentifierFromArray = ResourceIdentifier::fromArray(
            [
                "type" => "0",
                "id" => "0",
            ],
            new DefaultExceptionFactory()
        );

        $this->assertEquals($resourceIdentifier, $resourceIdentifierFromArray);
    }

    /**
     * @test
     */
    public function fromArray(): void
    {
        $resourceIdentifier = $this->createResourceIdentifier()
            ->setType("user")
            ->setId("1");

        $resourceIdentifierFromArray = ResourceIdentifier::fromArray(
            [
                "type" => "user",
                "id" => "1",
            ],
            new DefaultExceptionFactory()
        );

        $this->assertEquals($resourceIdentifier, $resourceIdentifierFromArray);
    }

    /**
     * @test
     */
    public function fromArrayWithMeta(): void
    {
        $resourceIdentifier = $this->createResourceIdentifier()
            ->setType("user")
            ->setId("1")
            ->setMeta(["abc" => "def"]);

        $resourceIdentifierFromArray = ResourceIdentifier::fromArray(
            [
                "type" => "user",
                "id" => "1",
                "meta" => ["abc" => "def"],
            ],
            new DefaultExceptionFactory()
        );

        $this->assertEquals($resourceIdentifier, $resourceIdentifierFromArray);
    }

    /**
     * @test
     */
    public function getType(): void
    {
        $link = $this->createResourceIdentifier()
            ->setType("abc");

        $id = $link->getType();

        $this->assertEquals("abc", $id);
    }

    /**
     * @test
     */
    public function getId(): void
    {
        $link = $this->createResourceIdentifier()
            ->setId("123");

        $id = $link->getId();

        $this->assertEquals("123", $id);
    }

    private function createResourceIdentifier(): ResourceIdentifier
    {
        return new ResourceIdentifier();
    }
}
