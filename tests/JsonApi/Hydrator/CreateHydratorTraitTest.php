<?php

declare(strict_types=1);

namespace Devleand\Yin\Tests\JsonApi\Hydrator;

use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Stream;
use LogicException;
use PHPUnit\Framework\TestCase;
use Devleand\Yin\JsonApi\Exception\ClientGeneratedIdNotSupported;
use Devleand\Yin\JsonApi\Exception\DataMemberMissing;
use Devleand\Yin\JsonApi\Exception\DefaultExceptionFactory;
use Devleand\Yin\JsonApi\Request\JsonApiRequest;
use Devleand\Yin\JsonApi\Serializer\JsonDeserializer;
use Devleand\Yin\Tests\JsonApi\Double\StubCreateHydrator;

use function json_encode;

class CreateHydratorTraitTest extends TestCase
{
    /**
     * @test
     */
    public function hydrateWhenBodyEmpty(): void
    {
        $body = [];

        $hydrator = $this->createHydrator(false, "1");

        $this->expectException(DataMemberMissing::class);
        $hydrator->hydrateForCreate($this->createRequest($body), new DefaultExceptionFactory(), []);
    }

    /**
     * @test
     */
    public function hydrateWhenGeneratingId(): void
    {
        $type = "user";
        $id = "1";
        $body = [
            "data" => [
                "type" => $type,
            ],
        ];

        $hydrator = $this->createHydrator(false, $id);
        $domainObject = $hydrator->hydrateForCreate($this->createRequest($body), new DefaultExceptionFactory(), []);
        $this->assertEquals(["id" => $id], $domainObject);
    }

    /**
     * @test
     */
    public function testHydrateWhenBodyDataIdNotSupported(): void
    {
        $type = "user";
        $id = "1";
        $body = [
            "data" => [
                "type" => $type,
                "id" => $id,
            ],
        ];

        $hydrator = $this->createHydrator(true, $id);

        $this->expectException(ClientGeneratedIdNotSupported::class);
        $hydrator->hydrateForCreate($this->createRequest($body), new DefaultExceptionFactory(), []);
    }

    /**
     * @test
     */
    public function hydrateBodyDataId(): void
    {
        $type = "user";
        $id = "1";
        $body = [
            "data" => [
                "type" => $type,
                "id" => $id,
            ],
        ];

        $hydrator = $this->createHydrator(false, $id);
        $domainObject = $hydrator->hydrateForCreate($this->createRequest($body), new DefaultExceptionFactory(), []);
        $this->assertEquals(["id" => $id], $domainObject);
    }

    /**
     * @test
     */
    public function validateRequest(): void
    {
        $type = "user";
        $id = "1";

        $body = [
            "data" => [
                "type" => $type,
                "id" => $id,
            ],
        ];

        $hydrator = $this->createHydrator(false, $id, true);

        $this->expectException(LogicException::class);
        $hydrator->hydrateForCreate($this->createRequest($body), new DefaultExceptionFactory(), []);
    }

    private function createRequest(array $body): JsonApiRequest
    {
        $data = json_encode($body);
        if ($data === false) {
            $data = "";
        }

        $psrRequest = new ServerRequest();
        $psrRequest = $psrRequest
            ->withParsedBody($body)
            ->withBody(new Stream("php://memory", "rw"));
        $psrRequest->getBody()->write($data);

        return new JsonApiRequest($psrRequest, new DefaultExceptionFactory(), new JsonDeserializer());
    }

    /**
     * @return StubCreateHydrator
     */
    private function createHydrator(
        bool $clientGeneratedIdException = false,
        string $generatedId = "",
        bool $logicException = false
    ) {
        return new StubCreateHydrator($clientGeneratedIdException, $generatedId, $logicException);
    }
}
