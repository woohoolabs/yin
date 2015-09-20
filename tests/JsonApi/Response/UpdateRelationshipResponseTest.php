<?php
namespace WoohooLabsTest\Yin\JsonApi\Response;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Request\Request;
use WoohooLabs\Yin\JsonApi\Response\UpdateRelationshipResponse;
use WoohooLabs\Yin\JsonApi\Transformer\ErrorDocument;
use WoohooLabsTest\Yin\JsonApi\Utils\StubSuccessfulDocument;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Response;

class UpdateRelationshipResponseTest extends PHPUnit_Framework_TestCase
{
    public function testOk()
    {
        $document = new StubSuccessfulDocument();

        $response = $this->createResponse()->ok($document, []);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testOkWithMeta()
    {
        $meta = ["abc" => "def"];
        $document = new StubSuccessfulDocument([], [], null, $meta);

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

    protected function createResponse()
    {
        return new UpdateRelationshipResponse(new Request(new ServerRequest()), new Response(), "");
    }
}
