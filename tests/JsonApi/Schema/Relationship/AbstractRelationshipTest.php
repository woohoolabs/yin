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
    public function createWithData(): void
    {
        $relationship = FakeRelationship::createWithData([], new StubResource());

        $data = $relationship->getRelationshipData();

        $this->assertEquals([], $data);
    }

    /**
     * @test
     */
    public function createWithLinks(): void
    {
        $relationship = FakeRelationship::createWithLinks(new RelationshipLinks());

        $links = $relationship->getLinks();

        $this->assertNotNull($links);
    }

    /**
     * @test
     */
    public function createWithMeta(): void
    {
        $relationship = FakeRelationship::createWithMeta(["abc" => "def"]);

        $meta = $relationship->getMeta();

        $this->assertEquals(["abc" => "def"], $meta);
    }

    /**
     * @test
     */
    public function setLinks(): void
    {
        $relationship = FakeRelationship::create();

        $relationship->setLinks(new RelationshipLinks());

        $this->assertNotNull($relationship->getLinks());
    }

    /**
     * @test
     */
    public function setData(): void
    {
        $relationship = $this->createRelationship();

        $relationship->setData(["id" => 1], new StubResource());

        $this->assertEquals(["id" => 1], $relationship->getRelationshipData());
    }

    /**
     * @test
     */
    public function setDataAsCallable(): void
    {
        $relationship = $this->createRelationship();

        $relationship->setDataAsCallable(
            function (): array {
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
    public function dataNotOmittedByDefault(): void
    {
        $relationship = $this->createRelationship();

        $isDataOmittedWhenNotIncluded = $relationship->isOmitDataWhenNotIncluded();

        $this->assertFalse($isDataOmittedWhenNotIncluded);
    }

    /**
     * @test
     */
    public function omitDataWhenNotIncluded(): void
    {
        $relationship = $this->createRelationship();

        $relationship->omitDataWhenNotIncluded();

        $this->assertTrue($relationship->isOmitDataWhenNotIncluded());
    }

    /**
     * @test
     */
    public function transformWithMeta(): void
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
    public function transformWithLinks(): void
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
    public function transformWhenNotIncludedField(): void
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
    public function transformWithEmptyData(): void
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
    public function transformWithEmptyOmittedData(): void
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

    /**
     * @test
     */
    public function transformWithEmptyOmittedDataWhenRelationship(): void
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
                "dummy",
                "dummy",
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

    private function createRelationship(): FakeRelationship
    {
        return new FakeRelationship();
    }
}
