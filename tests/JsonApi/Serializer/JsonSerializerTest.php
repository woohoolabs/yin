<?php

declare(strict_types=1);

namespace Devleand\Yin\Tests\JsonApi\Serializer;

use Laminas\Diactoros\Response;
use PHPUnit\Framework\TestCase;
use Devleand\Yin\JsonApi\Serializer\JsonSerializer;

use function json_encode;

class JsonSerializerTest extends TestCase
{
    /**
     * @test
     */
    public function serializeBody(): void
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
    public function getBodyAsString(): void
    {
        $response = new Response();
        $response->getBody()->write("abc");

        $serializer = new JsonSerializer();

        $this->assertEquals("abc", $serializer->getBodyAsString($response));
    }
}
