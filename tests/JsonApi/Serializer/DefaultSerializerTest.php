<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Serializer;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Serializer\DefaultSerializer;
use Zend\Diactoros\Response;

class DefaultSerializerTest extends TestCase
{
    /**
     * @test
     */
    public function setResponseCode()
    {
        $serializer = new DefaultSerializer();

        $response = $serializer->serialize(new Response(), 418, []);

        $this->assertEquals(418, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function setContentHeader()
    {
        $serializer = new DefaultSerializer();

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
                "id" => "tom"
            ]
        ];

        $serializer = new DefaultSerializer();

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

        $serializer = new DefaultSerializer();

        $this->assertEquals("abc", $serializer->getBodyAsString($response));
    }
}
