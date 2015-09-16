<?php
namespace WoohooLabsTest\Yin\JsonApi\Response;

use WoohooLabs\Yin\JsonApi\Request\Request;
use WoohooLabs\Yin\JsonApi\Response\UpdateResponse;
use WoohooLabs\Yin\JsonApi\Transformer\ErrorDocument;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Response;

class UpdateResponseTest extends UpdateRelationshipResponseTest
{
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

    protected function createResponse()
    {
        return new UpdateResponse(new Request(new ServerRequest()), new Response());
    }
}
