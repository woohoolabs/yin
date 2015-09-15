<?php
namespace WoohooLabsTest\Yin\JsonApi\Transformer;

use PHPUnit_Framework_TestCase;
use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Schema\Error;
use WoohooLabsTest\Yin\JsonApi\Utils\StubErrorDocument;
use Zend\Diactoros\Response;

class AbstractErrorDocumentTest extends PHPUnit_Framework_TestCase
{
    public function testGetErrors()
    {
        $error = (new Error())->setId("abc");

        $errorDocument = $this->createErrorDocument()->addError($error)->addError($error);
        $this->assertEquals([$error, $error], $errorDocument->getErrors());
    }

    public function testGetResponseWithoutError()
    {
        $response = $this->createErrorDocument()->getResponse(new Response());

        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals(["application/vnd.api+json"], $response->getHeader("Content-Type"));
    }

    public function testGetResponseWithDefinedResponseCode()
    {
        $response = $this
            ->createErrorDocument()
            ->getResponse(new Response(), 500);

        $this->assertEquals(500, $response->getStatusCode());
    }

    public function testGetResponseWithOneError()
    {
        $response = $this
            ->createErrorDocument()
            ->addError((new Error())->setStatus(404))
            ->getResponse(new Response());

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals(1, count($this->getErrorsContentFromResponse($response)));
    }

    public function testGetResponseWithMultipleErrors()
    {
        $response = $this
            ->createErrorDocument()
            ->addError((new Error())->setStatus(403))
            ->addError((new Error())->setStatus(404))
            ->addError((new Error())->setStatus(418))
            ->getResponse(new Response());

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals(3, count($this->getErrorsContentFromResponse($response)));
    }

    private function getErrorsContentFromResponse(ResponseInterface $response)
    {
        $result = json_decode($response->getBody(), true);

        return isset($result["errors"]) ? $result["errors"] : [];
    }

    /**
     * @return \WoohooLabs\Yin\JsonApi\Transformer\AbstractErrorDocument
     */
    private function createErrorDocument()
    {
        return new StubErrorDocument();
    }
}
