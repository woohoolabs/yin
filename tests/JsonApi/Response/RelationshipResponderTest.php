<?php
namespace WoohooLabs\Yin\Tests\JsonApi\Response;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;
use WoohooLabs\Yin\JsonApi\Request\Request;
use WoohooLabs\Yin\JsonApi\Response\RelationshipResponder;
use WoohooLabs\Yin\JsonApi\Serializer\DefaultSerializer;
use WoohooLabs\Yin\Tests\JsonApi\Double\StubSuccessfulDocument;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;

class RelationshipResponderTest extends TestCase
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

    private function createResponder()
    {
        return new RelationshipResponder(
            new Request(new ServerRequest(), new DefaultExceptionFactory()),
            new Response(),
            new DefaultExceptionFactory(),
            new DefaultSerializer(),
            ""
        );
    }
}
