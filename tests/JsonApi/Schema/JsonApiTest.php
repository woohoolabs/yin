<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Schema;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Schema\JsonApiObject;

class JsonApiTest extends TestCase
{
    /**
     * @test
     */
    public function getVersion()
    {
        $version = "1.0";

        $jsonApi = $this->createJsonApi($version);
        $this->assertEquals($version, $jsonApi->getVersion());
    }

    /**
     * @test
     */
    public function getMeta()
    {
        $meta = ["abc" => "def"];

        $jsonApi = $this->createJsonApi("", $meta);
        $this->assertEquals($meta, $jsonApi->getMeta());
    }

    /**
     * @test
     */
    public function setMeta()
    {
        $meta = ["abc" => "def"];

        $jsonApi = $this->createJsonApi("")->setMeta($meta);
        $this->assertEquals($meta, $jsonApi->getMeta());
    }

    /**
     * @test
     */
    public function transformWithEmptyVersion()
    {
        $version = "";
        $meta = ["abc" => "def"];

        $jsonApi = $this->createJsonApi($version, $meta);

        $transformedJsonApi = [
            "meta" => $meta
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

        $jsonApi = $this->createJsonApi($version, $meta);

        $transformedJsonApi = [
            "version" => $version
        ];
        $this->assertEquals($transformedJsonApi, $jsonApi->transform());
    }

    private function createJsonApi($version, array $meta = [])
    {
        return new JsonApiObject($version, $meta);
    }
}
