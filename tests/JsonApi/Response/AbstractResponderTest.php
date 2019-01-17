<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Response;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;
use WoohooLabs\Yin\JsonApi\Response\Responder;
use WoohooLabs\Yin\JsonApi\Schema\Document\ErrorDocument;
use WoohooLabs\Yin\JsonApi\Schema\Error;
use WoohooLabs\Yin\JsonApi\Serializer\JsonSerializer;
use WoohooLabs\Yin\Tests\JsonApi\Double\StubRequest;
use Zend\Diactoros\Response;

class AbstractResponderTest extends TestCase
{
    /**
     * @test
     */
    public function genericSuccess()
    {
        $response = $this->createResponder()->genericSuccess(201);
        $this->assertEquals(201, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function genericError()
    {
        $document = new ErrorDocument();
        $errors = [new Error(), new Error()];

        $response = $this->createResponder()->genericError($document, $errors, 418);
        $body = json_decode($response->getBody()->__toString(), true);

        $this->assertEquals(418, $response->getStatusCode());
        $this->assertCount(2, $body["errors"]);
    }

    private function createResponder(?Response $response = null): Responder
    {
        return new Responder(
            new StubRequest(),
            $response ?? new Response(),
            new DefaultExceptionFactory(),
            new JsonSerializer()
        );
    }
}
