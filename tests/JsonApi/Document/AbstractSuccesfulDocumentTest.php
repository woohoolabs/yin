<?php
namespace WoohooLabsTest\Yin\JsonApi\Transformer;

use PHPUnit_Framework_TestCase;
use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Request\Request;
use WoohooLabs\Yin\JsonApi\Schema\Data\SingleResourceData;
use WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface;
use WoohooLabs\Yin\JsonApi\Schema\JsonApi;
use WoohooLabs\Yin\JsonApi\Schema\Link;
use WoohooLabs\Yin\JsonApi\Schema\Links;
use WoohooLabsTest\Yin\JsonApi\Utils\StubSuccessfulDocument;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;

class AbstractSuccesfulDocumentTest extends PHPUnit_Framework_TestCase
{
    public function testGetResponse()
    {
        $responseCode = 200;
        $version = "1.0";

        $document = $this->createDocument([], [], new JsonApi($version));
        $response = $document->getMetaResponse(new Request(), new Response(), [], $responseCode);
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
        $data = new SingleResourceData();

        $document = $this->createDocument([], [], null, [], null, $data);
        $response = $document->getResponse(new Response(), [], $request, 200);
        $this->assertEmpty($this->getContentFromResponse("data", $response));
    }

    public function testGetResponseWithLinks()
    {
        $request = new Request(new ServerRequest());
        $links = new Links("http://example.com", ["self" => new Link("/users/1"), "related" => new Link("/people/1")]);

        $document = $this->createDocument([], [], null, [], $links);
        $response = $document->getResponse(new Response(), [], $request, 200);
        $this->assertCount(2, $this->getContentFromResponse("links", $response));
    }

    public function testGetEmptyDataResponseWithEmptyIncludes()
    {
        $request = new Request(new ServerRequest());
        $data = null;

        $document = $this->createDocument([], [], null, [], null, $data);
        $response = $document->getResponse(new Response(), [], $request, 200);
        $this->assertEquals([], $this->getContentFromResponse("included", $response));
    }

    public function testGetEmptyDataResponseWithIncludes()
    {
        $request = new Request(new ServerRequest());
        $data = new SingleResourceData();
        $data->setIncludedResources(
            [
                [
                    "type" => "user",
                    "id" => "1"
                ],
                [
                    "type" => "user",
                    "id" => "2"
                ]
            ]
        );

        $document = $this->createDocument([], [], null, [], null, $data);
        $response = $document->getResponse(new Response(), [], $request, 200);
        $this->assertEquals($data->transformIncludedResources(), $this->getContentFromResponse("included", $response));
    }

    public function testGetIncludes()
    {
        $data = new SingleResourceData();
        $data->setIncludedResources(
            [
                [
                    "type" => "user",
                    "id" => "1"
                ],
                [
                    "type" => "user",
                    "id" => "2"
                ]
            ]
        );

        $document = $this->createDocument([], [], null, [], null, $data);
        $this->assertNull($document->getData()->transformPrimaryResources());
        $document->getResponse(new Response(), [], new Request(new ServerRequest()), 200);
        $this->assertEquals($data, $document->getData());
    }

    public function testGetRelationshipResponse()
    {
        $request = new Request(new ServerRequest());
        $relationshipResponseContentData = [
            "type" => "user",
            "id" => "1"
        ];
        $relationshipResponseContent = [
            "data" => $relationshipResponseContentData
        ];

        $document = $this->createDocument([], [], null, [], null, null, $relationshipResponseContent);
        $response = $document->getRelationshipResponse("", new Response(), [], $request, 200);
        $this->assertEquals($relationshipResponseContentData, $this->getContentFromResponse("data", $response));
    }

    public function testGetRelationshipResponseWithIncluded()
    {
        $request = new Request(new ServerRequest());
        $data = new SingleResourceData();
        $data->setIncludedResources(
            [
                [
                    "type" => "user",
                    "id" => "1"
                ],
                [
                    "type" => "user",
                    "id" => "2"
                ]
            ]
        );

        $document = $this->createDocument([], [], null, [], null, $data, []);
        $response = $document->getRelationshipResponse("", new Response(), [], $request, 200);
        $this->assertEquals($data->transformIncludedResources(), $this->getContentFromResponse("included", $response));
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
     * @param \WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface|null $data
     * @param array $relationshipResponseContent
     * @return \WoohooLabs\Yin\JsonApi\Document\AbstractSuccessfulDocument
     */
    private function createDocument(
        array $extensions = [],
        array $supportedExtensions = [],
        JsonApi $jsonApi = null,
        array $meta = [],
        Links $links = null,
        DataInterface $data = null,
        array $relationshipResponseContent = []
    ) {
        return new StubSuccessfulDocument(
            $extensions,
            $supportedExtensions,
            $jsonApi,
            $meta,
            $links,
            $data,
            $relationshipResponseContent
        );
    }
}
