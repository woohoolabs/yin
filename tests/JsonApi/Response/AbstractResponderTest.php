<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Response;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Document\ErrorDocument;
use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;
use WoohooLabs\Yin\JsonApi\Request\Request;
use WoohooLabs\Yin\JsonApi\Response\Responder;
use WoohooLabs\Yin\JsonApi\Schema\Error;
use WoohooLabs\Yin\JsonApi\Serializer\DefaultSerializer;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;

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

    private function createResponder(Response $response = null): Responder
    {
        return new Responder(
            new Request(new ServerRequest(), new DefaultExceptionFactory()),
            $response ?? new Response(),
            new DefaultExceptionFactory(),
            new DefaultSerializer()
        );
    }
}
