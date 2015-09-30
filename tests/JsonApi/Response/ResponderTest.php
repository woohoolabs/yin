<?php
namespace WoohooLabsTest\Yin\JsonApi\Response;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Request\Request;
use WoohooLabs\Yin\JsonApi\Response\Responder;
use WoohooLabs\Yin\JsonApi\Schema\Link;
use WoohooLabs\Yin\JsonApi\Schema\Links;
use WoohooLabs\Yin\JsonApi\Transformer\ErrorDocument;
use WoohooLabsTest\Yin\JsonApi\Utils\StubSuccessfulDocument;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Response;

class ResponderTest extends PHPUnit_Framework_TestCase
{
    public function testOk()
    {
        $document = new StubSuccessfulDocument();

        $response = $this->createResponder()->ok($document, []);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testOkWithMeta()
    {
        $meta = ["abc" => "def"];
        $document = new StubSuccessfulDocument([], [], null, $meta);

        $response = $this->createResponder()->okWithMeta($document, []);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCreated()
    {
        $document = new StubSuccessfulDocument();

        $response = $this->createResponder()->created($document, []);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testCreatedWithLinks()
    {
        $href = "http://example.com/users";
        $document = new StubSuccessfulDocument([], [], null, [], Links::createAbsoluteWithSelf(new Link($href)));

        $response = $this->createResponder()->created($document, []);
        $this->assertEquals([$href], $response->getHeader("location"));
    }

    public function testAccepted()
    {
        $response = $this->createResponder()->accepted();
        $this->assertEquals(202, $response->getStatusCode());
    }

    public function testNoContent()
    {
        $response = $this->createResponder()->noContent();
        $this->assertEquals(204, $response->getStatusCode());
    }

    public function testForbidden()
    {
        $document = new ErrorDocument();

        $response = $this->createResponder()->forbidden($document, []);
        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testNotFound()
    {
        $document = new ErrorDocument();

        $response = $this->createResponder()->notFound($document, []);
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testConflict()
    {
        $document = new ErrorDocument();

        $response = $this->createResponder()->conflict($document, []);
        $this->assertEquals(409, $response->getStatusCode());
    }

    private function createResponder()
    {
        return new Responder(new Request(new ServerRequest()), new Response());
    }
}
