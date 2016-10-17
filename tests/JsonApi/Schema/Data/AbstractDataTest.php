<?php
namespace WoohooLabsTest\Yin\JsonApi\Schema\Data;

use PHPUnit\Framework\TestCase;
use WoohooLabsTest\Yin\JsonApi\Utils\DummyData;

class AbstractDataTest extends TestCase
{
    /**
     * @test
     */
    public function setPrimaryResources()
    {
        $dummyData = new DummyData();
        $dummyData->setPrimaryResources(
            [
                ["type" => "user", "id" => "1"],
                ["type" => "user", "id" => "2"]
            ]
        );

        $this->assertTrue($dummyData->hasPrimaryResource("user", "1"));
        $this->assertTrue($dummyData->hasPrimaryResource("user", "2"));
    }

    /**
     * @test
     */
    public function addNotYetIncludedPrimaryResource()
    {
        $dummyData = new DummyData();
        $dummyData->addPrimaryResource(["type" => "user", "id" => "1"]);

        $this->assertTrue($dummyData->hasPrimaryResource("user", "1"));
    }

    /**
     * @test
     */
    public function addAlreadyIncludedPrimaryResource()
    {
        $dummyData = new DummyData();
        $dummyData->addIncludedResource(["type" => "user", "id" => "1"]);
        $dummyData->addPrimaryResource(["type" => "user", "id" => "1"]);

        $this->assertFalse($dummyData->hasIncludedResource("user", "1"));
        $this->assertTrue($dummyData->hasPrimaryResource("user", "1"));
    }
}
