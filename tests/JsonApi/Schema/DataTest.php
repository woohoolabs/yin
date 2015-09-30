<?php
namespace WoohooLabsTest\Yin\JsonApi\Schema;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Schema\Data\SingleResourceData;

class DataTest extends PHPUnit_Framework_TestCase
{
    public function testGetNonExistentResource()
    {
        $resources = [
            [
                "type" => "resource",
                "id" => "1"
            ]
        ];

        $data = $this->createData()->setIncludedResources($resources);
        $this->assertNull($data->getResource("resource", "2"));
        $this->assertNull($data->getResource("resources", "1"));
    }

    public function testGetResource()
    {
        $resource = [
            "type" => "resource",
            "id" => "1"
        ];

        $data = $this->createData()->addIncludedResource($resource);
        $this->assertEquals($resource, $data->getResource("resource", "1"));
    }

    public function testIsEmptyByDefault()
    {
        $included = $this->createData();
        $this->assertFalse($included->hasIncludedResources());
    }

    public function testIsEmptyWhenIncludingNoResource()
    {
        $resources = [
            [
                "type" => "resource",
                "id" => "1"
            ]
        ];

        $data = $this->createData()->setIncludedResources($resources);
        $this->assertTrue($data->hasIncludedResources());
    }

    public function testIsEmptyWhenIncludingResources()
    {
        $resources = [];

        $data = $this->createData()->setIncludedResources($resources);
        $this->assertFalse($data->hasIncludedResources());
    }

    public function testAddResource()
    {
        $resource = [
            "type" => "resource",
            "id" => "1"
        ];

        $data = $this->createData()->addIncludedResource($resource);
        $this->assertEquals($resource, $data->getResource("resource", "1"));
    }

    public function testTransformEmpty()
    {
        $data = $this->createData();

        $this->assertEquals([], $data->transformIncludedResources());
    }

    public function testTransform()
    {
        $resource1 = ["type" => "resource", "id" => "1"];
        $resource2 = ["type" => "resource", "id" => "2"];
        $item1 = ["type" => "item", "id" => "1"];
        $item2 = ["type" => "item", "id" => "2"];

        $resources = [$item1, $resource2, $resource1, $item2, $item1, $resource1];

        $data = $this->createData()->setIncludedResources($resources);

        $transformedIncluded = [$item1, $item2, $resource1, $resource2];
        $this->assertEquals($transformedIncluded, $data->transformIncludedResources());
    }

    private function createData()
    {
        return new SingleResourceData();
    }
}
