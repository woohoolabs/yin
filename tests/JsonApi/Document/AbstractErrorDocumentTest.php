<?php
namespace WoohooLabsTest\Yin\JsonApi\Transformer;

use PHPUnit_Framework_TestCase;
use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Schema\Error;
use WoohooLabs\Yin\JsonApi\Serializer\DefaultSerializer;
use WoohooLabsTest\Yin\JsonApi\Utils\StubErrorDocument;
use Zend\Diactoros\Response;

class AbstractErrorDocumentTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function getErrors()
    {
        $error = (new Error())->setId("abc");

        $errorDocument = $this->createErrorDocument()->addError($error)->addError($error);
        $this->assertEquals([$error, $error], $errorDocument->getErrors());
    }

    /**
     * @test
     */
    public function getResponseWithoutError()
    {
        $response = $this->createErrorDocument()->getResponse(new DefaultSerializer(), new Response());
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals(["application/vnd.api+json"], $response->getHeader("Content-Type"));
    }

    /**
     * @test
     */
    public function getResponseWithDefinedResponseCode()
    {
        $response = $this
            ->createErrorDocument()
            ->getResponse(new DefaultSerializer(), new Response(), 500);

        $this->assertEquals(500, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function getResponseWithOneError()
    {
        $response = $this
            ->createErrorDocument()
            ->addError((new Error())->setStatus(404))
            ->getResponse(new DefaultSerializer(), new Response());

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertCount(1, $this->getErrorsContentFromResponse($response));
    }

    /**
     * @test
     */
    public function getResponseWithMultipleErrors()
    {
        $response = $this
            ->createErrorDocument()
            ->addError((new Error())->setStatus(403))
            ->addError((new Error())->setStatus(404))
            ->addError((new Error())->setStatus(418))
            ->getResponse(new DefaultSerializer(), new Response());

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertCount(3, $this->getErrorsContentFromResponse($response));
    }

    private function getErrorsContentFromResponse(ResponseInterface $response)
    {
        $result = json_decode($response->getBody(), true);

        return isset($result["errors"]) ? $result["errors"] : [];
    }

    /**
     * @return \WoohooLabs\Yin\JsonApi\Document\AbstractErrorDocument
     */
    private function createErrorDocument()
    {
        return new StubErrorDocument();
    }
}
