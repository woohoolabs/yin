<?php
namespace WoohooLabsTest\Yin\JsonApi\Response;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Request\Request;
use WoohooLabs\Yin\JsonApi\Response\FetchRelationshipResponse;
use WoohooLabs\Yin\JsonApi\Transformer\ErrorDocument;
use WoohooLabsTest\Yin\JsonApi\Utils\StubSuccessfulDocument;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Response;

class FetchRelationshipResponseTest extends PHPUnit_Framework_TestCase
{
    public function testOk()
    {
        $document = new StubSuccessfulDocument();

        $response = $this->createResponse()->ok($document, []);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testNotFound()
    {
        $document = new ErrorDocument();

        $response = $this->createResponse()->notFound($document, []);
        $this->assertEquals(404, $response->getStatusCode());
    }

    private function createResponse()
    {
        return new FetchRelationshipResponse(new Request(new ServerRequest()), new Response(), "");
    }
}
