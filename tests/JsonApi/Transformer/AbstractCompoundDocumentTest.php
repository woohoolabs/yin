<?php
namespace WoohooLabsTest\Yin\JsonApi\Transformer;

use PHPUnit_Framework_TestCase;
use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Request\Request;
use WoohooLabs\Yin\JsonApi\Schema\Included;
use WoohooLabs\Yin\JsonApi\Schema\JsonApi;
use WoohooLabs\Yin\JsonApi\Schema\Link;
use WoohooLabs\Yin\JsonApi\Schema\Links;
use WoohooLabsTest\Yin\JsonApi\Utils\StubCompoundDocument;
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

    public function testGetResponseWithLinks()
    {
        $request = new Request(new ServerRequest());
        $links = new Links("http://example.com", ["self" => new Link("/users/1"), "related" => new Link("/people/1")]);

        $document = $this->createDocument([], [], null, [], $links, []);
        $response = $document->getResponse(new Response(), [], $request, 200);
        $this->assertEquals(2, count($this->getContentFromResponse("links", $response)));
    }

    public function testGetEmptyDataResponseWithEmptyIncludes()
    {
        $request = new Request(new ServerRequest());
        $included = null;

        $document = $this->createDocument([], [], null, [], null, [], $included);
        $response = $document->getResponse(new Response(), [], $request, 200);
        $this->assertEquals([], $this->getContentFromResponse("included", $response));
    }

    public function testGetEmptyDataResponseWithIncludes()
    {
        $request = new Request(new ServerRequest());
        $included = new Included();
        $included->setResources(
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

        $document = $this->createDocument([], [], null, [], null, [], $included);
        $response = $document->getResponse(new Response(), [], $request, 200);
        $this->assertEquals($included->transform(), $this->getContentFromResponse("included", $response));
    }

    public function testGetIncludes()
    {
        $included = new Included();
        $included->setResources(
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

        $document = $this->createDocument([], [], null, [], null, [], $included);
        $this->assertEquals(null, $document->getIncluded());
        $document->getResponse(new Response(), [], new Request(new ServerRequest()), 200);
        $this->assertEquals($included, $document->getIncluded());
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

        $document = $this->createDocument([], [], null, [], null, [], null, $relationshipResponseContent);
        $response = $document->getRelationshipResponse("", new Response(), [], $request, 200);
        $this->assertEquals($relationshipResponseContentData, $this->getContentFromResponse("data", $response));
    }

    public function testGetRelationshipResponseWithIncluded()
    {
        $request = new Request(new ServerRequest());
        $included = new Included();
        $included->setResources(
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

        $document = $this->createDocument([], [], null, [], null, [], $included, []);
        $response = $document->getRelationshipResponse("", new Response(), [], $request, 200);
        $this->assertEquals($included->transform(), $this->getContentFromResponse("included", $response));
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
     * @param \WoohooLabs\Yin\JsonApi\Schema\Included|null $included
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
        Included $included = null,
        array $relationshipResponseContent = []
    ) {
        return new StubCompoundDocument(
            $extensions,
            $supportedExtensions,
            $jsonApi,
            $meta,
            $links,
            $data,
            $included,
            $relationshipResponseContent
        );
    }
}
