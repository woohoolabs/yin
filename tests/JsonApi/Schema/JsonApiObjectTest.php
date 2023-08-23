<?php

declare(strict_types=1);

namespace Devleand\Yin\Tests\JsonApi\Schema;

use PHPUnit\Framework\TestCase;
use Devleand\Yin\JsonApi\Schema\JsonApiObject;

class JsonApiObjectTest extends TestCase
{
    /**
     * @test
     */
    public function getVersion(): void
    {
        $jsonApi = $this->createJsonApiObject("1.0");

        $version = $jsonApi->getVersion();

        $this->assertEquals("1.0", $version);
    }

    /**
     * @test
     */
    public function getMeta(): void
    {
        $jsonApi = $this->createJsonApiObject("", ["abc" => "def"]);

        $meta = $jsonApi->getMeta();

        $this->assertEquals(["abc" => "def"], $meta);
    }

    /**
     * @test
     */
    public function setMeta(): void
    {
        $jsonApi = $this->createJsonApiObject("")
            ->setMeta(["abc" => "def"]);

        $meta = $jsonApi->getMeta();

        $this->assertEquals(["abc" => "def"], $meta);
    }

    /**
     * @test
     */
    public function transformWithEmptyVersion(): void
    {
        $jsonApi = $this->createJsonApiObject("", ["abc" => "def"]);

        $jsonApiObject = $jsonApi->transform();

        $this->assertEquals(
            [
                "meta" => ["abc" => "def"],
            ],
            $jsonApiObject
        );
    }

    /**
     * @test
     */
    public function transformWithEmptyMeta(): void
    {
        $jsonApi = $this->createJsonApiObject("1.0", ["abc" => "def"]);

        $jsonApiObject = $jsonApi->transform();

        $this->assertEquals(
            [
                "version" => "1.0",
                "meta" => ["abc" => "def"],
            ],
            $jsonApiObject
        );
    }

    private function createJsonApiObject(string $version, array $meta = []): JsonApiObject
    {
        return new JsonApiObject($version, $meta);
    }
}
