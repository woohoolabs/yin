<?php
namespace WoohooLabsTest\Yin\JsonApi\Response;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Request\Request;
use WoohooLabs\Yin\JsonApi\Response\UpdateResponse;
use WoohooLabs\Yin\JsonApi\Transformer\ErrorDocument;
use WoohooLabsTest\Yin\JsonApi\Utils\StubCompoundDocument;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Response;

class UpdateResponseTest extends PHPUnit_Framework_TestCase
{
    public function testOk()
    {
        $document = new StubCompoundDocument();

        $response = $this->createResponse()->ok($document, []);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testOkWithMeta()
    {
        $meta = ["abc" => "def"];
        $document = new StubCompoundDocument([], [], null, $meta);

        $response = $this->createResponse()->okWithMeta($document, []);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testAccepted()
    {
        $response = $this->createResponse()->accepted();
        $this->assertEquals(202, $response->getStatusCode());
    }

    public function testNoContent()
    {
        $response = $this->createResponse()->noContent();
        $this->assertEquals(204, $response->getStatusCode());
    }

    public function testForbidden()
    {
        $document = new ErrorDocument();

        $response = $this->createResponse()->forbidden($document, []);
        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testNotFound()
    {
        $document = new ErrorDocument();

        $response = $this->createResponse()->notFound($document, []);
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testConflict()
    {
        $document = new ErrorDocument();

        $response = $this->createResponse()->conflict($document, []);
        $this->assertEquals(409, $response->getStatusCode());
    }

    private function createResponse()
    {
        return new UpdateResponse(new Request(new ServerRequest()), new Response());
    }
}
