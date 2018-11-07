<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Schema;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Schema\JsonApiObject;

class JsonApiObjectTest extends TestCase
{
    /**
     * @test
     */
    public function getVersion()
    {
        $jsonApi = $this->createJsonApiObject("1.0");

        $version = $jsonApi->getVersion();

        $this->assertEquals("1.0", $version);
    }

    /**
     * @test
     */
    public function getMeta()
    {
        $jsonApi = $this->createJsonApiObject("", ["abc" => "def"]);

        $meta = $jsonApi->getMeta();

        $this->assertEquals(["abc" => "def"], $meta);
    }

    /**
     * @test
     */
    public function setMeta()
    {
        $jsonApi = $this->createJsonApiObject("")
            ->setMeta(["abc" => "def"]);

        $meta = $jsonApi->getMeta();

        $this->assertEquals(["abc" => "def"], $meta);
    }

    /**
     * @test
     */
    public function transformWithEmptyVersion()
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
    public function transformWithEmptyMeta()
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

    private function createJsonApiObject($version, array $meta = []): JsonApiObject
    {
        return new JsonApiObject($version, $meta);
    }
}
