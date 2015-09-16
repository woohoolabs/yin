<?php
namespace WoohooLabsTest\Yin\JsonApi\Response;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Request\Request;
use WoohooLabs\Yin\JsonApi\Response\DeleteResponse;
use WoohooLabsTest\Yin\JsonApi\Utils\StubCompoundDocument;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Response;

class DeleteResponseTest extends PHPUnit_Framework_TestCase
{
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

    private function createResponse()
    {
        return new DeleteResponse(new Request(new ServerRequest()), new Response());
    }
}
