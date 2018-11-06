<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Schema\Document;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;
use WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface;
use WoohooLabs\Yin\JsonApi\Schema\Data\SingleResourceData;
use WoohooLabs\Yin\JsonApi\Schema\Document\AbstractResourceDocument;
use WoohooLabs\Yin\JsonApi\Schema\JsonApiObject;
use WoohooLabs\Yin\JsonApi\Schema\Link\DocumentLinks;
use WoohooLabs\Yin\JsonApi\Schema\Link\Link;
use WoohooLabs\Yin\Tests\JsonApi\Double\StubRequest;
use WoohooLabs\Yin\Tests\JsonApi\Double\StubResourceDocument;

class AbstractSuccessfulDocumentTest extends TestCase
{
    /**
     * @test
     */
    public function getContent()
    {
        $document = $this->createDocument(new JsonApiObject("1.0"));
        $content = $document->getMetaContent([]);

        $this->assertArrayHasKey("jsonapi", $content);
        $this->assertArrayHasKey("version", $content["jsonapi"]);
        $this->assertEquals("1.0", $content["jsonapi"]["version"]);
    }

    /**
     * @test
     */
    public function getEmptyMetaContent()
    {
        $document = $this->createDocument(null, []);
        $content = $document->getMetaContent([]);

        $this->assertArrayNotHasKey("meta", $content);
    }

    /**
     * @test
     */
    public function getMetaContent()
    {
        $document = $this->createDocument(null, ["abc" => "def"]);
        $content = $document->getMetaContent([]);

        $this->assertArrayHasKey("meta", $content);
        $this->assertEquals(["abc" => "def"], $content["meta"]);
    }

    /**
     * @test
     */
    public function getEmptyDataContent()
    {
        $request = new StubRequest();
        $data = new SingleResourceData();

        $document = $this->createDocument(null, [], null, $data);
        $content = $document->getContent(
            $request,
            new DefaultExceptionFactory(),
            []
        );

        $this->assertArrayHasKey("data", $content);
        $this->assertEmpty($content["data"]);
    }

    /**
     * @test
     */
    public function getContentWithLinks()
    {
        $request = new StubRequest();
        $links = new DocumentLinks("http://example.com", ["self" => new Link("/users/1"), "related" => new Link("/people/1")]);

        $document = $this->createDocument(null, [], $links);
        $content = $document->getContent(
            $request,
            new DefaultExceptionFactory(),
            []
        );

        $this->assertArrayHasKey("links", $content);
        $this->assertCount(2, $content["links"]);
    }

    /**
     * @test
     */
    public function getEmptyDataContentWithoutIncludes()
    {
        $request = new StubRequest();
        $data = null;

        $document = $this->createDocument(null, [], null, $data);
        $content = $document->getContent(
            $request,
            new DefaultExceptionFactory(),
            []
        );

        $this->assertArrayNotHasKey("included", $content);
    }

    /**
     * @test
     */
    public function getEmptyDataContentWithEmptyIncludes()
    {
        $request = new StubRequest(["include" => "animal"]);
        $data = null;

        $document = $this->createDocument(null, [], null, $data);
        $content = $document->getContent(
            $request,
            new DefaultExceptionFactory(),
            []
        );

        $this->assertArrayHasKey("included", $content);
        $this->assertEmpty($content["included"]);
    }

    /**
     * @test
     */
    public function getEmptyDataContentWithIncludes()
    {
        $request = new StubRequest();
        $data = new SingleResourceData();
        $data->setIncludedResources(
            [
                [
                    "type" => "user",
                    "id" => "1",
                ],
                [
                    "type" => "user",
                    "id" => "2",
                ],
            ]
        );

        $document = $this->createDocument(null, [], null, $data);
        $content = $document->getContent(
            $request,
            new DefaultExceptionFactory(),
            []
        );

        $this->assertArrayHasKey("included", $content);
        $this->assertEquals($data->transformIncluded(), $content["included"]);
    }

    /**
     * @test
     */
    public function getRelationshipContent()
    {
        $request = new StubRequest();
        $relationshipContent = [
            "type" => "user",
            "id" => "1",
        ];
        $relationshipContentData = [
            "data" => $relationshipContent,
        ];

        $document = $this->createDocument(null, [], null, null, $relationshipContentData);
        $content = $document->getRelationshipContent(
            "",
            $request,
            new DefaultExceptionFactory(),
            []
        );

        $this->assertArrayHasKey("data", $content);
        $this->assertEquals($relationshipContent, $content["data"]);
    }

    /**
     * @test
     */
    public function getRelationshipWithIncluded()
    {
        $request = new StubRequest();
        $data = new SingleResourceData();
        $data->setIncludedResources(
            [
                [
                    "type" => "user",
                    "id" => "1",
                ],
                [
                    "type" => "user",
                    "id" => "2",
                ],
            ]
        );

        $document = $this->createDocument(null, [], null, $data, []);
        $content = $document->getRelationshipContent(
            "",
            $request,
            new DefaultExceptionFactory(),
            []
        );

        $this->assertArrayHasKey("included", $content);
        $this->assertEquals($data->transformIncluded(), $content["included"]);
    }

    private function createDocument(
        ?JsonApiObject $jsonApi = null,
        array $meta = [],
        ?DocumentLinks $links = null,
        ?DataInterface $data = null,
        array $relationshipResponseContent = []
    ): AbstractResourceDocument {
        return new StubResourceDocument(
            $jsonApi,
            $meta,
            $links,
            $data,
            $relationshipResponseContent
        );
    }
}
