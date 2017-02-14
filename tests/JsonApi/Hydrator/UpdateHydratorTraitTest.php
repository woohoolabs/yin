<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Hydrator;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Exception\DataMemberMissing;
use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;
use WoohooLabs\Yin\JsonApi\Exception\ResourceIdMissing;
use WoohooLabs\Yin\JsonApi\Request\Request;
use WoohooLabs\Yin\JsonApi\Serializer\DefaultDeserializer;
use WoohooLabs\Yin\Tests\JsonApi\Double\StubUpdateHydrator;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Stream;

class UpdateHydratorTraitTest extends TestCase
{
    /**
     * @test
     */
    public function hydrateWhenBodyEmpty()
    {
        $body = [];

        $hydrator = $this->createHydrator();

        $this->expectException(DataMemberMissing::class);
        $hydrator->hydrateForUpdate($this->createRequest($body), new DefaultExceptionFactory(), []);
    }

    /**
     * @test
     */
    public function hydrateWhenIdMissing()
    {
        $body = [
            "data" => [
                "type" => "user"
            ]
        ];

        $hydrator = $this->createHydrator();

        $this->expectException(ResourceIdMissing::class);
        $hydrator->hydrateForUpdate($this->createRequest($body), new DefaultExceptionFactory(), []);
    }

    /**
     * @test
     */
    public function hydrateId()
    {
        $id = "1";
        $body = [
            "data" => [
                "type" => "user",
                "id" => $id
            ]
        ];

        $hydrator = $this->createHydrator();
        $domainObject = $hydrator->hydrateForUpdate($this->createRequest($body), new DefaultExceptionFactory(), []);
        $this->assertEquals(["id" => $id], $domainObject);
    }

    private function createRequest(array $body)
    {
        $psrRequest = new ServerRequest();
        $psrRequest = $psrRequest
            ->withParsedBody($body)
            ->withBody(new Stream("php://memory", "rw"));
        $psrRequest->getBody()->write(json_encode($body));

        $request = new Request($psrRequest, new DefaultExceptionFactory(), new DefaultDeserializer());

        return $request;
    }

    private function createHydrator(): StubUpdateHydrator
    {
        return new StubUpdateHydrator();
    }
}
