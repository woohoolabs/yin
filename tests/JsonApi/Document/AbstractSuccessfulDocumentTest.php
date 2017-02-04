<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Transformer;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;
use WoohooLabs\Yin\JsonApi\Request\Request;
use WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface;
use WoohooLabs\Yin\JsonApi\Schema\Data\SingleResourceData;
use WoohooLabs\Yin\JsonApi\Schema\JsonApi;
use WoohooLabs\Yin\JsonApi\Schema\Link;
use WoohooLabs\Yin\JsonApi\Schema\Links;
use WoohooLabs\Yin\Tests\JsonApi\Double\StubSuccessfulDocument;
use Zend\Diactoros\ServerRequest;

class AbstractSuccessfulDocumentTest extends TestCase
{
    /**
     * @test
     */
    public function getContent()
    {
        $request = new Request(new ServerRequest(), new DefaultExceptionFactory());

        $version = "1.0";

        $document = $this->createDocument(new JsonApi($version));
        $content = $document->getMetaContent(
            $request,
            new DefaultExceptionFactory(),
            []
        );

        $this->assertArrayHasKey('jsonapi', $content);
        $this->assertArrayHasKey('version', $content['jsonapi']);
        $this->assertEquals('1.0', $content['jsonapi']['version']);
    }

    /**
     * @test
     */
    public function getEmptyMetaContent()
    {
        $request = new Request(new ServerRequest(), new DefaultExceptionFactory());
        $meta = [];

        $document = $this->createDocument(null, $meta);
        $content = $document->getMetaContent(
            $request,
            new DefaultExceptionFactory(),
            []
        );

        $this->assertArrayNotHasKey('meta', $content);
    }

    /**
     * @test
     */
    public function getMetaContent()
    {
        $request = new Request(new ServerRequest(), new DefaultExceptionFactory());
        $meta = ["abc" => "def"];

        $document = $this->createDocument(null, $meta);
        $content = $document->getMetaContent(
            $request,
            new DefaultExceptionFactory(),
            []
        );

        $this->assertArrayHasKey('meta', $content);
        $this->assertEquals($meta, $content['meta']);
    }

    /**
     * @test
     */
    public function getEmptyDataContent()
    {
        $request = new Request(new ServerRequest(), new DefaultExceptionFactory());
        $data = new SingleResourceData();

        $document = $this->createDocument(null, [], null, $data);
        $content = $document->getContent(
            $request,
            new DefaultExceptionFactory(),
            []
        );

        $this->assertArrayHasKey('data', $content);
        $this->assertEmpty($content['data']);
    }

    /**
     * @test
     */
    public function getContentWithLinks()
    {
        $request = new Request(new ServerRequest(), new DefaultExceptionFactory());
        $links = new Links("http://example.com", ["self" => new Link("/users/1"), "related" => new Link("/people/1")]);

        $document = $this->createDocument(null, [], $links);
        $content = $document->getContent(
            $request,
            new DefaultExceptionFactory(),
            []
        );

        $this->assertArrayHasKey('links', $content);
        $this->assertCount(2, $content['links']);
    }

    /**
     * @test
     */
    public function getEmptyDataContentWithEmptyIncludes()
    {
        $request = new Request(new ServerRequest(), new DefaultExceptionFactory());
        $data = null;

        $document = $this->createDocument(null, [], null, $data);
        $content = $document->getContent(
            $request,
            new DefaultExceptionFactory(),
            []
        );

        $this->assertArrayNotHasKey('included', $content);
    }

    /**
     * @test
     */
    public function getEmptyDataContentWithIncludes()
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
        $content = $document->getContent(
            $request,
            new DefaultExceptionFactory(),
            []
        );

        $this->assertArrayHasKey('included', $content);
        $this->assertEquals($data->transformIncludedResources(), $content['included']);
    }

    /**
     * @test
     */
    public function getRelationshipContent()
    {
        $request = new Request(new ServerRequest(), new DefaultExceptionFactory());
        $relationshipContent = [
            "type" => "user",
            "id" => "1"
        ];
        $relationshipContentData = [
            "data" => $relationshipContent
        ];

        $document = $this->createDocument(null, [], null, null, $relationshipContentData);
        $content = $document->getRelationship(
            "",
            $request,
            new DefaultExceptionFactory(),
            []
        );

        $this->assertArrayHasKey('data', $content);
        $this->assertEquals($relationshipContent, $content['data']);
    }

    /**
     * @test
     */
    public function getRelationshipWithIncluded()
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
        $content = $document->getRelationship(
            "",
            $request,
            new DefaultExceptionFactory(),
            []
        );

        $this->assertArrayHasKey('included', $content);
        $this->assertEquals($data->transformIncludedResources(), $content['included']);
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
