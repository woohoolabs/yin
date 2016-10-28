<?php
namespace WoohooLabs\Yin\Tests\JsonApi\Schema\Data;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Schema\Data\SingleResourceData;

class SingleResourceDataTest extends TestCase
{
    /**
     * @test
     */
    public function transformSinglePrimaryResources()
    {
        $data = new SingleResourceData();
        $data->addPrimaryResource(["type" => "user", "id" => "1"]);

        $this->assertEquals(["type" => "user", "id" => "1"], $data->transformPrimaryResources());
    }

    /**
     * @test
     */
    public function transformMultiplePrimaryResources()
    {
        $data = new SingleResourceData();
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
}
