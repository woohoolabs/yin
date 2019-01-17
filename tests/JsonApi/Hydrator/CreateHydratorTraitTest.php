<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Hydrator;

use LogicException;
use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Exception\ClientGeneratedIdNotSupported;
use WoohooLabs\Yin\JsonApi\Exception\DataMemberMissing;
use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;
use WoohooLabs\Yin\JsonApi\Request\JsonApiRequest;
use WoohooLabs\Yin\JsonApi\Serializer\JsonDeserializer;
use WoohooLabs\Yin\Tests\JsonApi\Double\StubCreateHydrator;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Stream;

class CreateHydratorTraitTest extends TestCase
{
    /**
     * @test
     */
    public function hydrateWhenBodyEmpty()
    {
        $body = [];

        $hydrator = $this->createHydrator(false, "1");

        $this->expectException(DataMemberMissing::class);
        $hydrator->hydrateForCreate($this->createRequest($body), new DefaultExceptionFactory(), []);
    }

    /**
     * @test
     */
    public function hydrateWhenGeneratingId()
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
    public function testHydrateWhenBodyDataIdNotSupported()
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
    public function hydrateBodyDataId()
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
    public function validateRequest()
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

    private function createRequest(array $body)
    {
        $psrRequest = new ServerRequest();
        $psrRequest = $psrRequest
            ->withParsedBody($body)
            ->withBody(new Stream("php://memory", "rw"));
        $psrRequest->getBody()->write(json_encode($body));

        $request = new JsonApiRequest($psrRequest, new DefaultExceptionFactory(), new JsonDeserializer());

        return $request;
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
