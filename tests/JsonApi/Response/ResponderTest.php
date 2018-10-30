<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Response;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;
use WoohooLabs\Yin\JsonApi\Response\Responder;
use WoohooLabs\Yin\JsonApi\Schema\Document\ErrorDocument;
use WoohooLabs\Yin\JsonApi\Schema\Link\DocumentLinks;
use WoohooLabs\Yin\JsonApi\Schema\Link\Link;
use WoohooLabs\Yin\JsonApi\Serializer\JsonSerializer;
use WoohooLabs\Yin\Tests\JsonApi\Double\StubRequest;
use WoohooLabs\Yin\Tests\JsonApi\Double\StubSuccessfulDocument;
use Zend\Diactoros\Response;

class ResponderTest extends TestCase
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
        $document = new StubSuccessfulDocument(null, $meta);

        $response = $this->createResponder()->okWithMeta($document, []);
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function okWithRelationship()
    {
        $document = new StubSuccessfulDocument();

        $response = $this->createResponder()->okWithRelationship("", $document, []);
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
        $document = new StubSuccessfulDocument(null, [], new DocumentLinks("", ["self" => new Link($href)]));

        $response = $this->createResponder()->created($document, []);
        $this->assertEquals([$href], $response->getHeader("location"));
    }

    /**
     * @test
     */
    public function createdWithRelationship()
    {
        $document = new StubSuccessfulDocument();

        $response = $this->createResponder()->createdWithRelationship("", $document, []);
        $this->assertEquals(201, $response->getStatusCode());
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

    private function createResponder(): Responder
    {
        return new Responder(
            new StubRequest(),
            new Response(),
            new DefaultExceptionFactory(),
            new JsonSerializer()
        );
    }
}
