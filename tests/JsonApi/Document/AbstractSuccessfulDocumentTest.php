<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Transformer;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Document\AbstractSuccessfulDocument;
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
        $document = $this->createDocument(new JsonApi("1.0"));
        $content = $document->getMetaContent([]);

        $this->assertArrayHasKey('jsonapi', $content);
        $this->assertArrayHasKey('version', $content['jsonapi']);
        $this->assertEquals('1.0', $content['jsonapi']['version']);
    }

    /**
     * @test
     */
    public function getEmptyMetaContent()
    {
        $document = $this->createDocument(null, []);
        $content = $document->getMetaContent([]);

        $this->assertArrayNotHasKey('meta', $content);
    }

    /**
     * @test
     */
    public function getMetaContent()
    {
        $document = $this->createDocument(null, ["abc" => "def"]);
        $content = $document->getMetaContent([]);

        $this->assertArrayHasKey('meta', $content);
        $this->assertEquals(["abc" => "def"], $content['meta']);
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

    private function createDocument(
        JsonApi $jsonApi = null,
        array $meta = [],
        Links $links = null,
        DataInterface $data = null,
        array $relationshipResponseContent = []
    ): AbstractSuccessfulDocument {
        return new StubSuccessfulDocument(
            $jsonApi,
            $meta,
            $links,
            $data,
            $relationshipResponseContent
        );
    }
}
