<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Serializer;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Serializer\JsonDeserializer;
use Zend\Diactoros\ServerRequest;

class JsonDeserializerTest extends TestCase
{
    /**
     * @test
     */
    public function deserializeEmptyBody()
    {
        $request = $this->createRequestWithJsonBody("");

        $deserializer = new JsonDeserializer();

        $this->expectException(InvalidArgumentException::class);
        $deserializer->deserialize($request);
    }

    /**
     * @test
     */
    public function deserialize()
    {
        $parsedBody = [
            "data" => [
                "type" => "cat",
                "id" => "tom",
            ],
        ];

        $request = $this->createRequestWithJsonBody($parsedBody);

        $this->assertEquals($parsedBody, $request->getParsedBody());
    }

    private function createRequestWithJsonBody($body): ServerRequest
    {
        $request = new ServerRequest();

        return $request->withParsedBody($body);
    }
}
