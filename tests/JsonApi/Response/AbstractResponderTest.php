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
        $statusCode = 201;

        $response = $this->createResponder()->genericSuccess($statusCode);
        $this->assertEquals($statusCode, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function genericError()
    {
        $statusCode = 418;
        $document = new ErrorDocument();
        $errors = [new Error(), new Error()];

        $response = $this->createResponder()->genericError($document, $errors, $statusCode);
        $this->assertEquals($statusCode, $response->getStatusCode());
        $body = json_decode($response->getBody(), true);
        $this->assertCount(2, $body["errors"]);
    }

    /**
     * @param \Zend\Diactoros\Response $response
     * @return \WoohooLabs\Yin\JsonApi\Response\Responder
     */
    private function createResponder(Response $response = null)
    {
        return new Responder(
            new Request(new ServerRequest(), new DefaultExceptionFactory()),
            $response ? $response : new Response(),
            new DefaultExceptionFactory(),
            new DefaultSerializer()
        );
    }
}
