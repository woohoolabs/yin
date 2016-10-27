<?php
namespace WoohooLabsTest\Yin\JsonApi\Schema\Relationship;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;
use WoohooLabs\Yin\JsonApi\Transformer\Transformation;
use WoohooLabsTest\Yin\JsonApi\Double\DummyData;
use WoohooLabsTest\Yin\JsonApi\Double\FakeRelationship;
use WoohooLabsTest\Yin\JsonApi\Double\StubRequest;
use WoohooLabsTest\Yin\JsonApi\Double\StubResourceTransformer;

class AbstractRelationshipTest extends TestCase
{
    /**
     * @test
     */
    public function setData()
    {
        $relationship = $this->createRelationship();

        $relationship->setData(["id" => 1], new StubResourceTransformer());
        $this->assertEquals(
            ["id" => 1],
            $relationship->getRetrieveData()
        );
    }

    /**
     * @test
     */
    public function setDataAsCallable()
    {
        $relationship = $this->createRelationship();

        $relationship->setDataAsCallable(
            function() {
                return ["id" => 1];
            },
            new StubResourceTransformer()
        );
        $this->assertEquals(
            ["id" => 1],
            $relationship->getRetrieveData()
        );
    }

    /**
     * @test
     */
    public function DataNotOmittedWhenNotIncludedByDefault()
    {
        $relationship = $this->createRelationship();

        $this->assertFalse($relationship->isOmitWhenNotIncluded());
    }

    /**
     * @test
     */
    public function omitDataWhenNotIncluded()
    {
        $relationship = $this->createRelationship();

        $relationship->omitWhenNotIncluded();
        $this->assertTrue($relationship->isOmitWhenNotIncluded());
    }

    /**
     * @test
     */
    public function transformEmpty()
    {
        $relationship = $this->createRelationship();

        $result = $relationship->transform(
            new Transformation(new StubRequest(), new DummyData(), new DefaultExceptionFactory(), ""),
            "type",
            "name",
            [],
            []
        );
        $this->assertEquals(
            [
                "data" => []
            ],
            $result
        );
    }

    /**
     * @return FakeRelationship
     */
    private function createRelationship()
    {
        return new FakeRelationship();
    }
}
