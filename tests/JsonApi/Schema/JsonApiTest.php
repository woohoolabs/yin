<?php
namespace WoohooLabsTest\Yin\JsonApi\Schema;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Schema\JsonApi;

class JsonApiTest extends PHPUnit_Framework_TestCase
{
    public function testGetVersion()
    {
        $version = "1.0";

        $jsonApi = $this->createJsonApi($version);
        $this->assertEquals($version, $jsonApi->getVersion());
    }

    public function testGetMeta()
    {
        $meta = ["abc" => "def"];

        $jsonApi = $this->createJsonApi("", $meta);
        $this->assertEquals($meta, $jsonApi->getMeta());
    }

    public function testSetMeta()
    {
        $meta = ["abc" => "def"];

        $jsonApi = $this->createJsonApi("")->setMeta($meta);
        $this->assertEquals($meta, $jsonApi->getMeta());
    }

    public function testTransformWithEmptyVersion()
    {
        $version = "";
        $meta = ["abc" => "def"];

        $jsonApi = $this->createJsonApi($version, $meta);

        $transformedJsonApi = [
            "meta" => $meta
        ];
        $this->assertEquals($transformedJsonApi, $jsonApi->transform());
    }

    public function testTransformWithEmptyMeta()
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
        return new JsonApi($version, $meta);
    }
}
