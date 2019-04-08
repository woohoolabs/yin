<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Schema\Relationship;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;
use WoohooLabs\Yin\JsonApi\Schema\Link\RelationshipLinks;
use WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformation;
use WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformer;
use WoohooLabs\Yin\Tests\JsonApi\Double\DummyData;
use WoohooLabs\Yin\Tests\JsonApi\Double\FakeRelationship;
use WoohooLabs\Yin\Tests\JsonApi\Double\StubJsonApiRequest;
use WoohooLabs\Yin\Tests\JsonApi\Double\StubResource;

class AbstractRelationshipTest extends TestCase
{
    /**
     * @test
     */
    public function createWithData()
    {
        $relationship = FakeRelationship::createWithData([], new StubResource());

        $data = $relationship->getRelationshipData();

        $this->assertEquals([], $data);
    }

    /**
     * @test
     */
    public function createWithLinks()
    {
        $relationship = FakeRelationship::createWithLinks(new RelationshipLinks());

        $links = $relationship->getLinks();

        $this->assertNotNull($links);
    }

    /**
     * @test
     */
    public function createWithMeta()
    {
        $relationship = FakeRelationship::createWithMeta(["abc" => "def"]);

        $meta = $relationship->getMeta();

        $this->assertEquals(["abc" => "def"], $meta);
    }

    /**
     * @test
     */
    public function setLinks()
    {
        $relationship = FakeRelationship::create();

        $relationship->setLinks(new RelationshipLinks());

        $this->assertNotNull($relationship->getLinks());
    }

    /**
     * @test
     */
    public function setData()
    {
        $relationship = $this->createRelationship();

        $relationship->setData(["id" => 1], new StubResource());

        $this->assertEquals(["id" => 1], $relationship->getRelationshipData());
    }

    /**
     * @test
     */
    public function setDataAsCallable()
    {
        $relationship = $this->createRelationship();

        $relationship->setDataAsCallable(
            function () {
                return ["id" => 1];
            },
            new StubResource()
        );
        $data = $relationship->getRelationshipData();

        $this->assertEquals(
            ["id" => 1],
            $data
        );
    }

    /**
     * @test
     */
    public function dataNotOmittedByDefault()
    {
        $relationship = $this->createRelationship();

        $isDataOmittedWhenNotIncluded = $relationship->isOmitDataWhenNotIncluded();

        $this->assertFalse($isDataOmittedWhenNotIncluded);
    }

    /**
     * @test
     */
    public function omitDataWhenNotIncluded()
    {
        $relationship = $this->createRelationship();

        $relationship->omitDataWhenNotIncluded();

        $this->assertTrue($relationship->isOmitDataWhenNotIncluded());
    }

    /**
     * @test
     */
    public function transformWithMeta()
    {
        $relationship = $this->createRelationship()
            ->setMeta(["abc" => "def"]);

        $relationshipObject = $relationship->transform(
            new ResourceTransformation(
                new StubResource(),
                [],
                "",
                new StubJsonApiRequest(),
                "",
                "",
                "",
                new DefaultExceptionFactory()
            ),
            new ResourceTransformer(),
            new DummyData(),
            []
        );

        $this->assertEquals(
            [
                "meta" => [
                    "abc" => "def",
                ],
                "data" => [],
            ],
            $relationshipObject
        );
    }

    /**
     * @test
     */
    public function transformWithLinks()
    {
        $relationship = $this->createRelationship()
            ->setLinks(new RelationshipLinks());

        $relationshipObject = $relationship->transform(
            new ResourceTransformation(
                new StubResource(),
                [],
                "",
                new StubJsonApiRequest(),
                "",
                "",
                "",
                new DefaultExceptionFactory()
            ),
            new ResourceTransformer(),
            new DummyData(),
            []
        );

        $this->assertEquals(
            [
                "links" => [],
                "data" => [],
            ],
            $relationshipObject
        );
    }

    /**
     * @test
     */
    public function transformWhenNotIncludedField()
    {
        $relationship = $this->createRelationship();

        $relationshipObject = $relationship->transform(
            new ResourceTransformation(
                new StubResource("user1"),
                [],
                "user1",
                new StubJsonApiRequest(["fields" => ["user1" => ""]]),
                "",
                "rel",
                "rel",
                new DefaultExceptionFactory()
            ),
            new ResourceTransformer(),
            new DummyData(),
            []
        );

        $this->assertNull($relationshipObject);
    }

    /**
     * @test
     */
    public function transformWithEmptyData()
    {
        $relationship = $this->createRelationship();

        $relationshipObject = $relationship->transform(
            new ResourceTransformation(
                new StubResource(),
                [],
                "",
                new StubJsonApiRequest(),
                "",
                "",
                "",
                new DefaultExceptionFactory()
            ),
            new ResourceTransformer(),
            new DummyData(),
            []
        );

        $this->assertEquals(
            [
                "data" => [],
            ],
            $relationshipObject
        );
    }

    /**
     * @test
     */
    public function transformWithEmptyOmittedData()
    {
        $relationship = $this->createRelationship()
            ->omitDataWhenNotIncluded();

        $relationshipObject = $relationship->transform(
            new ResourceTransformation(
                new StubResource(),
                [],
                "",
                new StubJsonApiRequest(),
                "",
                "",
                "",
                new DefaultExceptionFactory()
            ),
            new ResourceTransformer(),
            new DummyData(),
            []
        );

        $this->assertEquals(
            [],
            $relationshipObject
        );
    }

    private function createRelationship(): FakeRelationship
    {
        return new FakeRelationship();
    }
}
