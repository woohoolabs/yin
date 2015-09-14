<?php
namespace WoohooLabsTest\Yin\JsonApi\Transformer;

use PHPUnit_Framework_TestCase;
use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Request\Request;
use WoohooLabs\Yin\JsonApi\Schema\JsonApi;
use WoohooLabs\Yin\JsonApi\Schema\Links;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractCompoundDocument;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;

class AbstractCompoundDocumentTest extends PHPUnit_Framework_TestCase
{
    public function testGetResponse()
    {
        $responseCode = 200;
        $version = "1.0";

        $document = $this->createDocument([], [], new JsonApi($version));
        $response = $document->getMetaResponse(new Response(), [], $responseCode);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(["application/vnd.api+json"], $response->getHeader("Content-Type"));
        $this->assertEquals("1.0", $this->getContentFromResponse("jsonApi", $response)["version"]);
    }

    public function testGetResponseWithExtensions()
    {
        $extensions = ["ext1", "ext2"];
        $supportedExtensions = ["ext1", "ext2", "ext3"];

        $document = $this->createDocument($extensions, $supportedExtensions, null, []);
        $response = $document->getMetaResponse(new Response(), [], 200);
        $this->assertEquals(
            ['application/vnd.api+json; ext="ext1,ext2"; supported-ext="ext1,ext2,ext3"'],
            $response->getHeader("Content-Type")
        );
    }

    public function testGetEmptyMetaResponse()
    {
        $meta = [];
        $responseCode = 200;

        $document = $this->createDocument([], [], null, $meta);
        $response = $document->getMetaResponse(new Response(), [], $responseCode);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($meta, $this->getContentFromResponse("meta", $response));
    }

    public function testGetMetaResponse()
    {
        $meta = ["abc" => "def"];

        $document = $this->createDocument([], [], null, $meta);
        $response = $document->getMetaResponse(new Response(), [], 200);
        $this->assertEquals($meta, $this->getContentFromResponse("meta", $response));
    }

    public function testGetEmptyDataResponse()
    {
        $request = new Request(new ServerRequest());
        $data = [];

        $document = $this->createDocument([], [], null, [], null, $data);
        $response = $document->getResponse(new Response(), [], $request, 200);
        $this->assertEquals($data, $this->getContentFromResponse("data", $response));
    }

    public function testGetEmptyDataResponseWithEmptyIncludes()
    {
        $request = new Request(new ServerRequest());
        $included = [];

        $document = $this->createDocument([], [], null, [], null, [], $included);
        $response = $document->getResponse(new Response(), [], $request, 200);
        $this->assertEquals($included, $this->getContentFromResponse("included", $response));
    }

    /**
     * @param string $key
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return array
     */
    private function getContentFromResponse($key, ResponseInterface $response)
    {
        $result = json_decode($response->getBody(), true);

        return isset($result[$key]) ? $result[$key] : [];
    }

    /**
     * @param array $extensions
     * @param array $supportedExtensions
     * @param \WoohooLabs\Yin\JsonApi\Schema\JsonApi|null $jsonApi
     * @param array $meta
     * @param \WoohooLabs\Yin\JsonApi\Schema\Links|null $links
     * @param array $data
     * @param array $included
     * @param array $relationshipResponseContent
     * @return \WoohooLabs\Yin\JsonApi\Transformer\AbstractCompoundDocument
     */
    private function createDocument(
        array $extensions = [],
        array $supportedExtensions = [],
        JsonApi $jsonApi = null,
        array $meta = [],
        Links $links = null,
        array $data = [],
        array $included = [],
        array $relationshipResponseContent = []
    ) {
        $mock = $this->getMockForAbstractClass(
            AbstractCompoundDocument::class,
            [],
            "",
            true,
            true,
            true,
            ["getExtensions", "getSupportedExtensions"]
        );

        $mock
            ->method("getExtensions")
            ->willReturn($extensions);
        $mock
            ->method("getSupportedExtensions")
            ->willReturn($supportedExtensions);
        $mock
            ->method("getJsonApi")
            ->willReturn($jsonApi);
        $mock
            ->method("getMeta")
            ->withAnyParameters()
            ->willReturn($meta);
        $mock
            ->method("getLinks")
            ->withAnyParameters()
            ->willReturn($links);
        $mock
            ->method("setContent")
            ->withAnyParameters();
        $mock
            ->method("getRelationshipContent")
            ->withAnyParameters()
            ->willReturn($relationshipResponseContent);

        $this->setObjectProperty($mock, "data", $data);
        $this->setObjectProperty($mock, "included", $included);

        return $mock;
    }

    /**
     * @param mixed $object
     * @param string $propertyName
     * @param mixed $value
     */
    private function setObjectProperty($object, $propertyName, $value)
    {
        $reflection = new \ReflectionClass($object);
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);
        $property->setValue($object, $value);
    }
}
