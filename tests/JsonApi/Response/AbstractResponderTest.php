<?php
namespace WoohooLabsTest\Yin\JsonApi\Response;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Document\ErrorDocument;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactory;
use WoohooLabs\Yin\JsonApi\Request\Request;
use WoohooLabs\Yin\JsonApi\Response\Responder;
use WoohooLabs\Yin\JsonApi\Schema\Error;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;

class AbstractResponderTest extends PHPUnit_Framework_TestCase
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
            new Request(new ServerRequest(), new ExceptionFactory()),
            $response ? $response : new Response(),
            new ExceptionFactory()
        );
    }
}
