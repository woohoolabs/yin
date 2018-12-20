<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Serializer;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Serializer\JsonSerializer;
use Zend\Diactoros\Response;
use function json_encode;

class JsonSerializerTest extends TestCase
{
    /**
     * @test
     */
    public function serializeBody()
    {
        $serializer = new JsonSerializer();

        $response = $serializer->serialize(
            new Response(),
            [
                "data" => [
                    "type" => "cat",
                    "id" => "tom",
                ],
            ]
        );

        $this->assertEquals(
            json_encode(
                [
                    "data" => [
                        "type" => "cat",
                        "id" => "tom",
                    ],
                ]
            ),
            $response->getBody()->__toString()
        );
    }

    /**
     * @test
     */
    public function getBodyAsString()
    {
        $response = new Response();
        $response->getBody()->write("abc");

        $serializer = new JsonSerializer();

        $this->assertEquals("abc", $serializer->getBodyAsString($response));
    }
}
