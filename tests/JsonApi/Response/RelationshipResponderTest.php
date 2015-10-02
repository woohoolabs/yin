<?php
namespace WoohooLabsTest\Yin\JsonApi\Response;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactory;
use WoohooLabs\Yin\JsonApi\Request\Request;
use WoohooLabs\Yin\JsonApi\Response\RelationshipResponder;
use WoohooLabsTest\Yin\JsonApi\Utils\StubSuccessfulDocument;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Response;

class RelationshipResponderTest extends PHPUnit_Framework_TestCase
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

    private function createResponder()
    {
        return new RelationshipResponder(new Request(new ServerRequest()), new Response(), new ExceptionFactory(), "");
    }
}
