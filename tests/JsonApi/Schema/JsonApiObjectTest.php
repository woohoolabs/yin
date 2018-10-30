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
        $version = "1.0";

        $jsonApi = $this->createJsonApiObject($version);
        $this->assertEquals($version, $jsonApi->getVersion());
    }

    /**
     * @test
     */
    public function getMeta()
    {
        $meta = ["abc" => "def"];

        $jsonApi = $this->createJsonApiObject("", $meta);
        $this->assertEquals($meta, $jsonApi->getMeta());
    }

    /**
     * @test
     */
    public function setMeta()
    {
        $meta = ["abc" => "def"];

        $jsonApi = $this->createJsonApiObject("")->setMeta($meta);
        $this->assertEquals($meta, $jsonApi->getMeta());
    }

    /**
     * @test
     */
    public function transformWithEmptyVersion()
    {
        $version = "";
        $meta = ["abc" => "def"];

        $jsonApi = $this->createJsonApiObject($version, $meta);

        $transformedJsonApi = [
            "meta" => $meta,
        ];
        $this->assertEquals($transformedJsonApi, $jsonApi->transform());
    }

    /**
     * @test
     */
    public function transformWithEmptyMeta()
    {
        $version = "1.0";
        $meta = [];

        $jsonApi = $this->createJsonApiObject($version, $meta);

        $transformedJsonApi = [
            "version" => $version,
        ];
        $this->assertEquals($transformedJsonApi, $jsonApi->transform());
    }

    private function createJsonApiObject($version, array $meta = []): JsonApiObject
    {
        return new JsonApiObject($version, $meta);
    }
}
