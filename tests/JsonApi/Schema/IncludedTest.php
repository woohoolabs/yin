<?php
namespace WoohooLabsTest\Yin\JsonApi\Schema;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Schema\Included;

class IncludedTest extends PHPUnit_Framework_TestCase
{
    public function testGetNonExistentResource()
    {
        $resources = [
            [
                "type" => "resource",
                "id" => "1"
            ]
        ];

        $included = $this->createIncluded()->setResources($resources);
        $this->assertNull($included->getResource("resource", "2"));
        $this->assertNull($included->getResource("resources", "1"));
    }

    public function testGetResource()
    {
        $resource = [
            "type" => "resource",
            "id" => "1"
        ];

        $included = $this->createIncluded()->addResource($resource);
        $this->assertEquals($resource, $included->getResource("resource", "1"));
    }

    public function testIsEmptyByDefault()
    {
        $included = $this->createIncluded();
        $this->assertTrue($included->isEmpty());
    }

    public function testIsEmptyWhenIncludingNoResource()
    {
        $resources = [
            [
                "type" => "resource",
                "id" => "1"
            ]
        ];

        $included = $this->createIncluded()->setResources($resources);
        $this->assertFalse($included->isEmpty());
    }

    public function testIsEmptyWhenIncludingResources()
    {
        $resources = [];

        $included = $this->createIncluded()->setResources($resources);
        $this->assertTrue($included->isEmpty());
    }

    public function testAddResource()
    {
        $resource = [
            "type" => "resource",
            "id" => "1"
        ];

        $included = $this->createIncluded()->addResource($resource);
        $this->assertEquals($resource, $included->getResource("resource", "1"));
    }

    public function testTransformEmpty()
    {
        $included = $this->createIncluded();

        $this->assertEquals([], $included->transform());
    }

    public function testTransform()
    {
        $resource1 = ["type" => "resource", "id" => "1"];
        $resource2 = ["type" => "resource", "id" => "2"];
        $item1 = ["type" => "item", "id" => "1"];
        $item2 = ["type" => "item", "id" => "2"];

        $resources = [$item1, $resource2, $resource1, $item2, $item1, $resource1];

        $included = $this->createIncluded()->setResources($resources);

        $transformedIncluded = [$item1, $item2, $resource1, $resource2];
        $this->assertEquals($transformedIncluded, $included->transform());
    }

    private function createIncluded()
    {
        return new Included();
    }
}
