<?php
namespace WoohooLabsTest\Yin\JsonApi\Response;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Request\Request;
use WoohooLabs\Yin\JsonApi\Response\Responder;
use WoohooLabs\Yin\JsonApi\Schema\Error;
use WoohooLabs\Yin\JsonApi\Transformer\ErrorDocument;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Response;

class AbstractResponderTest extends PHPUnit_Framework_TestCase
{
    public function testGenericSuccess()
    {
        $statusCode = 201;

        $response = $this->createResponder()->genericSuccess($statusCode);
        $this->assertEquals($statusCode, $response->getStatusCode());
    }

    public function testGenericError()
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
        return new Responder(new Request(new ServerRequest()), $response ? $response : new Response());
    }
}
