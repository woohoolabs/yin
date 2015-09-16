<?php
namespace WoohooLabsTest\Yin\JsonApi\Hydrator;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Request\Request;
use WoohooLabsTest\Yin\JsonApi\Utils\StubUpdateHydrator;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Stream;

class UpdateHydratorTraitTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \WoohooLabs\Yin\JsonApi\Exception\ResourceTypeMissing
     */
    public function testHydrateWhenBodyEmpty()
    {
        $body = [];

        $hydrator = $this->createHydrator();
        $hydrator->hydrateForUpdate($this->createRequest($body), []);
    }

    /**
     * @expectedException \WoohooLabs\Yin\JsonApi\Exception\ResourceTypeMissing
     */
    public function testHydrateWhenBodyDataEmpty()
    {
        $body = [
            "data" => []
        ];

        $hydrator = $this->createHydrator();
        $hydrator->hydrateForUpdate($this->createRequest($body), []);
    }

    /**
     * @expectedException \WoohooLabs\Yin\JsonApi\Exception\ResourceIdMissing
     */
    public function testHydrateWhenIdMissing()
    {
        $body = [
            "data" => [
                "type" => "user"
            ]
        ];

        $hydrator = $this->createHydrator();
        $hydrator->hydrateForUpdate($this->createRequest($body), []);
    }

    public function testHydrateId()
    {
        $id = "1";
        $body = [
            "data" => [
                "type" => "user",
                "id" => $id
            ]
        ];

        $hydrator = $this->createHydrator();
        $domainObject = $hydrator->hydrateForUpdate($this->createRequest($body), []);
        $this->assertEquals(["id" => $id], $domainObject);
    }

    private function createRequest(array $body)
    {
        $psrRequest = new ServerRequest();
        $psrRequest = $psrRequest
            ->withParsedBody($body)
            ->withBody(new Stream("php://memory", "rw"));
        $psrRequest->getBody()->write(json_encode($body));

        $request = new Request($psrRequest);

        return $request;
    }

    /**
     * @return \WoohooLabs\Yin\JsonApi\Hydrator\UpdateHydratorTrait
     */
    private function createHydrator()
    {
        return new StubUpdateHydrator();
    }
}
