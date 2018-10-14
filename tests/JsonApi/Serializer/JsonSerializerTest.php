<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Serializer;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Serializer\JsonSerializer;
use Zend\Diactoros\Response;

class JsonSerializerTest extends TestCase
{
    /**
     * @test
     */
    public function setResponseCode()
    {
        $serializer = new JsonSerializer();

        $response = $serializer->serialize(new Response(), 418, []);

        $this->assertEquals(418, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function setContentHeader()
    {
        $serializer = new JsonSerializer();

        $response = $serializer->serialize(new Response(), 200, []);

        $this->assertEquals(["application/vnd.api+json"], $response->getHeader("Content-Type"));
    }

    /**
     * @test
     */
    public function serializeBody()
    {
        $content = [
            "data" => [
                "type" => "cat",
                "id" => "tom",
            ],
        ];

        $serializer = new JsonSerializer();

        $response = $serializer->serialize(new Response(), 200, $content);

        $this->assertEquals(json_encode($content), $response->getBody()->__toString());
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
