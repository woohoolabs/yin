<?php
namespace WoohooLabsTest\Yin\JsonApi\Schema\Data;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Schema\Data\CollectionData;

class CollectionDataTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function transformSinglePrimaryResourcesInTheOrderDefined()
    {
        $data = new CollectionData();
        $data->addPrimaryResource(["type" => "user", "id" => "1"]);

        $this->assertEquals(
            [
                ["type" => "user", "id" => "1"]
            ],
            $data->transformPrimaryResources()
        );
    }

    /**
     * @test
     */
    public function transformMultiplePrimaryResourcesInTheOrderDefined()
    {
        $data = new CollectionData();
        $data->setPrimaryResources(
            [
                ["type" => "user", "id" => "1"],
                ["type" => "user", "id" => "2"],
                ["type" => "dog", "id" => "4"],
                ["type" => "dog", "id" => "3"],
                ["type" => "user", "id" => "3"],
            ]
        );

        $this->assertEquals(
            [
                ["type" => "user", "id" => "1"],
                ["type" => "user", "id" => "2"],
                ["type" => "dog", "id" => "4"],
                ["type" => "dog", "id" => "3"],
                ["type" => "user", "id" => "3"],
            ],
            $data->transformPrimaryResources()
        );
    }
}
