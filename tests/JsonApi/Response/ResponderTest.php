<?php
namespace WoohooLabsTest\Yin\JsonApi\Response;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Document\ErrorDocument;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactory;
use WoohooLabs\Yin\JsonApi\Request\Request;
use WoohooLabs\Yin\JsonApi\Response\Responder;
use WoohooLabs\Yin\JsonApi\Schema\Link;
use WoohooLabs\Yin\JsonApi\Schema\Links;
use WoohooLabsTest\Yin\JsonApi\Utils\StubSuccessfulDocument;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;

class ResponderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function ok()
    {
        $document = new StubSuccessfulDocument();

        $response = $this->createResponder()->ok($document, []);
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function okWithMeta()
    {
        $meta = ["abc" => "def"];
        $document = new StubSuccessfulDocument([], [], null, $meta);

        $response = $this->createResponder()->okWithMeta($document, []);
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function created()
    {
        $document = new StubSuccessfulDocument();

        $response = $this->createResponder()->created($document, []);
        $this->assertEquals(201, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function createdWithLinks()
    {
        $href = "http://example.com/users";
        $document = new StubSuccessfulDocument([], [], null, [], new Links("", ["self" => new Link($href)]));

        $response = $this->createResponder()->created($document, []);
        $this->assertEquals([$href], $response->getHeader("location"));
    }

    /**
     * @test
     */
    public function accepted()
    {
        $response = $this->createResponder()->accepted();
        $this->assertEquals(202, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function noContent()
    {
        $response = $this->createResponder()->noContent();
        $this->assertEquals(204, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function forbidden()
    {
        $document = new ErrorDocument();

        $response = $this->createResponder()->forbidden($document, []);
        $this->assertEquals(403, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function notFound()
    {
        $document = new ErrorDocument();

        $response = $this->createResponder()->notFound($document, []);
        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function conflict()
    {
        $document = new ErrorDocument();

        $response = $this->createResponder()->conflict($document, []);
        $this->assertEquals(409, $response->getStatusCode());
    }

    private function createResponder()
    {
        return new Responder(
        	new Request(new ServerRequest(), new ExceptionFactory()),
			new Response(),
			new ExceptionFactory()
		);
    }
}
