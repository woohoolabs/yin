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
use WoohooLabs\Yin\Tests\JsonApi\Double\StubRequest;
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
    public function dataNotOmittedWhenNotIncludedByDefault()
    {
        $relationship = $this->createRelationship();

        $omit = $relationship->isOmitWhenNotIncluded();

        $this->assertFalse($omit);
    }

    /**
     * @test
     */
    public function omitDataWhenNotIncluded()
    {
        $relationship = $this->createRelationship();

        $relationship->omitWhenNotIncluded();
        $omit = $relationship->isOmitWhenNotIncluded();

        $this->assertTrue($omit);
    }

    /**
     * @test
     */
    public function transformEmpty()
    {
        $relationship = $this->createRelationship();

        $relationshipObject = $relationship->transform(
            new ResourceTransformation(
                new StubResource(),
                [],
                "",
                new StubRequest(),
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

    private function createRelationship(): FakeRelationship
    {
        return new FakeRelationship();
    }
}
