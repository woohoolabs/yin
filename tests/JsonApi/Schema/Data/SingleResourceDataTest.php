<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Schema;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Schema\Data\SingleResourceData;

class SingleResourceDataTest extends TestCase
{
    /**
     * @test
     */
    public function getNonExistentResource()
    {
        $resources = [
            [
                "type" => "resource",
                "id" => "1",
            ],
        ];

        $data = $this->createData()->setIncludedResources($resources);
        $this->assertNull($data->getResource("resource", "2"));
        $this->assertNull($data->getResource("resources", "1"));
    }

    /**
     * @test
     */
    public function getResource()
    {
        $resource = [
            "type" => "resource",
            "id" => "1",
        ];

        $data = $this->createData()->addIncludedResource($resource);
        $this->assertEquals($resource, $data->getResource("resource", "1"));
    }

    /**
     * @test
     */
    public function isEmptyByDefault()
    {
        $included = $this->createData();
        $this->assertFalse($included->hasIncludedResources());
    }

    /**
     * @test
     */
    public function isEmptyWhenIncludingNoResource()
    {
        $resources = [
            [
                "type" => "resource",
                "id" => "1",
            ],
        ];

        $data = $this->createData()->setIncludedResources($resources);
        $this->assertTrue($data->hasIncludedResources());
    }

    /**
     * @test
     */
    public function isEmptyWhenIncludingResources()
    {
        $resources = [];

        $data = $this->createData()->setIncludedResources($resources);
        $this->assertFalse($data->hasIncludedResources());
    }

    /**
     * @test
     */
    public function addResource()
    {
        $resource = [
            "type" => "resource",
            "id" => "1",
        ];

        $data = $this->createData()->addIncludedResource($resource);
        $this->assertEquals($resource, $data->getResource("resource", "1"));
    }

    /**
     * @test
     */
    public function transformEmpty()
    {
        $data = $this->createData();

        $this->assertEquals([], $data->transformIncludedResources());
    }

    /**
     * @test
     */
    public function transform()
    {
        $data = $this->createData()->setIncludedResources([
            ["type" => "item", "id" => "1"],
            ["type" => "resource", "id" => "2"],
            ["type" => "resource", "id" => "1"],
            ["type" => "item", "id" => "2"],
            ["type" => "item", "id" => "1"],
            ["type" => "resource", "id" => "2"],
        ]);

        $this->assertEquals(
            [
                ["type" => "item", "id" => "1"],
                ["type" => "resource", "id" => "2"],
                ["type" => "resource", "id" => "1"],
                ["type" => "item", "id" => "2"],
            ],
            $data->transformIncludedResources()
        );
    }

    /**
     * @test
     */
    public function transformSinglePrimaryResources()
    {
        $data = $this->createData();

        $data->addPrimaryResource(["type" => "user", "id" => "1"]);

        $this->assertEquals(["type" => "user", "id" => "1"], $data->transformPrimaryResources());
    }

    /**
     * @test
     */
    public function transformMultiplePrimaryResources()
    {
        $data = $this->createData();

        $data->setPrimaryResources(
            [
                ["type" => "user", "id" => "1"],
                ["type" => "user", "id" => "2"],
                ["type" => "dog", "id" => "4"],
                ["type" => "dog", "id" => "3"],
                ["type" => "user", "id" => "3"],
            ]
        );

        $this->assertEquals(["type" => "user", "id" => "1"], $data->transformPrimaryResources());
    }

    private function createData(): SingleResourceData
    {
        return new SingleResourceData();
    }
}
