<?php
namespace WoohooLabsTest\Yin\JsonApi\Response;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Request\Request;
use WoohooLabs\Yin\JsonApi\Response\FetchResponse;
use WoohooLabs\Yin\JsonApi\Schema\Error;
use WoohooLabs\Yin\JsonApi\Transformer\ErrorDocument;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Response;

class AbstractResponseTest extends PHPUnit_Framework_TestCase
{
    public function testGenericSuccess()
    {
        $statusCode = 201;

        $response = $this->createResponse()->genericSuccess($statusCode);
        $this->assertEquals($statusCode, $response->getStatusCode());
    }

    public function testGenericError()
    {
        $statusCode = 418;
        $document = new ErrorDocument();
        $errors = [new Error(), new Error()];

        $response = $this->createResponse()->genericError($statusCode, $document, $errors);
        $this->assertEquals($statusCode, $response->getStatusCode());
        $body = json_decode($response->getBody(), true);
        $this->assertCount(2, $body["errors"]);
    }

    public function testGetResponse()
    {
        $statusCode = 418;
        $originalResponse = new Response();
        $originalResponse = $originalResponse->withStatus($statusCode);

        $response = $this->createResponse($originalResponse)->getResponse();
        $this->assertEquals($originalResponse, $response);
    }

    /**
     * @param \Zend\Diactoros\Response $response
     * @return \WoohooLabs\Yin\JsonApi\Response\AbstractResponse
     */
    private function createResponse(Response $response = null)
    {
        return new FetchResponse(new Request(new ServerRequest()), $response ? $response : new Response());
    }
}
