<?php
namespace WoohooLabsTest\Yin\JsonApi\Response;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Request\Request;
use WoohooLabs\Yin\JsonApi\Response\CreateResponse;
use WoohooLabs\Yin\JsonApi\Schema\Link;
use WoohooLabs\Yin\JsonApi\Schema\Links;
use WoohooLabs\Yin\JsonApi\Transformer\ErrorDocument;
use WoohooLabsTest\Yin\JsonApi\Utils\StubCompoundDocument;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Response;

class CreateResponseTest extends PHPUnit_Framework_TestCase
{
    public function testCreated()
    {
        $document = new StubCompoundDocument();

        $response = $this->createResponse()->created($document, []);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testCreatedWithLinks()
    {
        $href = "http://example.com/users";
        $document = new StubCompoundDocument([], [], null, [], Links::createAbsoluteWithSelf(new Link($href)));

        $response = $this->createResponse()->created($document, []);
        $this->assertEquals([$href], $response->getHeader("location"));
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

    public function testConflict()
    {
        $document = new ErrorDocument();

        $response = $this->createResponse()->conflict($document, []);
        $this->assertEquals(409, $response->getStatusCode());
    }

    private function createResponse()
    {
        return new CreateResponse(new Request(new ServerRequest()), new Response());
    }
}
