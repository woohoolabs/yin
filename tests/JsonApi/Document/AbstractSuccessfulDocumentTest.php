<?php
namespace WoohooLabs\Yin\Tests\JsonApi\Transformer;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;
use WoohooLabs\Yin\JsonApi\Request\Request;
use WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface;
use WoohooLabs\Yin\JsonApi\Schema\Data\SingleResourceData;
use WoohooLabs\Yin\JsonApi\Schema\JsonApi;
use WoohooLabs\Yin\JsonApi\Schema\Link;
use WoohooLabs\Yin\JsonApi\Schema\Links;
use WoohooLabs\Yin\JsonApi\Serializer\DefaultSerializer;
use WoohooLabs\Yin\Tests\JsonApi\Double\StubSuccessfulDocument;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;

class AbstractSuccessfulDocumentTest extends TestCase
{
    /**
     * @test
     */
    public function getResponse()
    {
        $request = new Request(new ServerRequest(), new DefaultExceptionFactory());
        $responseCode = 200;
        $version = "1.0";

        $document = $this->createDocument(new JsonApi($version));
        $response = $document->getMetaResponse(
            $request,
            new Response(),
            new DefaultExceptionFactory(),
            new DefaultSerializer(),
            [],
            $responseCode
        );
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(["application/vnd.api+json"], $response->getHeader("Content-Type"));
        $this->assertEquals("1.0", $this->getContentFromResponse("jsonapi", $response)["version"]);
    }

    /**
     * @test
     */
    public function getEmptyMetaResponse()
    {
        $request = new Request(new ServerRequest(), new DefaultExceptionFactory());
        $meta = [];
        $responseCode = 200;

        $document = $this->createDocument(null, $meta);
        $response = $document->getMetaResponse(
            $request,
            new Response(),
            new DefaultExceptionFactory(),
            new DefaultSerializer(),
            [],
            $responseCode
        );
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($meta, $this->getContentFromResponse("meta", $response));
    }

    /**
     * @test
     */
    public function getMetaResponse()
    {
        $request = new Request(new ServerRequest(), new DefaultExceptionFactory());
        $meta = ["abc" => "def"];

        $document = $this->createDocument(null, $meta);
        $response = $document->getMetaResponse(
            $request,
            new Response(),
            new DefaultExceptionFactory(),
            new DefaultSerializer(),
            [],
            200
        );
        $this->assertEquals($meta, $this->getContentFromResponse("meta", $response));
    }

    /**
     * @test
     */
    public function getEmptyDataResponse()
    {
        $request = new Request(new ServerRequest(), new DefaultExceptionFactory());
        $data = new SingleResourceData();

        $document = $this->createDocument(null, [], null, $data);
        $response = $document->getResponse(
            $request,
            new Response(),
            new DefaultExceptionFactory(),
            new DefaultSerializer(),
            [],
            200
        );
        $this->assertEmpty($this->getContentFromResponse("data", $response));
    }

    /**
     * @test
     */
    public function getResponseWithLinks()
    {
        $request = new Request(new ServerRequest(), new DefaultExceptionFactory());
        $links = new Links("http://example.com", ["self" => new Link("/users/1"), "related" => new Link("/people/1")]);

        $document = $this->createDocument(null, [], $links);
        $response = $document->getResponse(
            $request,
            new Response(),
            new DefaultExceptionFactory(),
            new DefaultSerializer(),
            [],
            200
        );
        $this->assertCount(2, $this->getContentFromResponse("links", $response));
    }

    /**
     * @test
     */
    public function getEmptyDataResponseWithEmptyIncludes()
    {
        $request = new Request(new ServerRequest(), new DefaultExceptionFactory());
        $data = null;

        $document = $this->createDocument(null, [], null, $data);
        $response = $document->getResponse(
            $request,
            new Response(),
            new DefaultExceptionFactory(),
            new DefaultSerializer(),
            [],
            200
        );
        $this->assertEquals([], $this->getContentFromResponse("included", $response));
    }

    /**
     * @test
     */
    public function getEmptyDataResponseWithIncludes()
    {
        $request = new Request(new ServerRequest(), new DefaultExceptionFactory());
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

        $document = $this->createDocument(null, [], null, $data);
        $response = $document->getResponse(
            $request,
            new Response(),
            new DefaultExceptionFactory(),
            new DefaultSerializer(),
            [],
            200
        );
        $this->assertEquals($data->transformIncludedResources(), $this->getContentFromResponse("included", $response));
    }

    /**
     * @test
     */
    public function getRelationshipResponse()
    {
        $request = new Request(new ServerRequest(), new DefaultExceptionFactory());
        $relationshipResponseContentData = [
            "type" => "user",
            "id" => "1"
        ];
        $relationshipResponseContent = [
            "data" => $relationshipResponseContentData
        ];

        $document = $this->createDocument(null, [], null, null, $relationshipResponseContent);
        $response = $document->getRelationshipResponse(
            "",
            $request,
            new Response(),
            new DefaultExceptionFactory(),
            new DefaultSerializer(),
            [],
            200
        );
        $this->assertEquals($relationshipResponseContentData, $this->getContentFromResponse("data", $response));
    }

    /**
     * @test
     */
    public function getRelationshipResponseWithIncluded()
    {
        $request = new Request(new ServerRequest(), new DefaultExceptionFactory());
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

        $document = $this->createDocument(null, [], null, $data, []);
        $response = $document->getRelationshipResponse(
            "",
            $request,
            new Response(),
            new DefaultExceptionFactory(),
            new DefaultSerializer(),
            [],
            200
        );
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
     * @param \WoohooLabs\Yin\JsonApi\Schema\JsonApi|null $jsonApi
     * @param array $meta
     * @param \WoohooLabs\Yin\JsonApi\Schema\Links|null $links
     * @param \WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface|null $data
     * @param array $relationshipResponseContent
     * @return \WoohooLabs\Yin\JsonApi\Document\AbstractSuccessfulDocument
     */
    private function createDocument(
        JsonApi $jsonApi = null,
        array $meta = [],
        Links $links = null,
        DataInterface $data = null,
        array $relationshipResponseContent = []
    ) {
        return new StubSuccessfulDocument(
            $jsonApi,
            $meta,
            $links,
            $data,
            $relationshipResponseContent
        );
    }
}
