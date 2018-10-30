<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Schema\Relationship;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;
use WoohooLabs\Yin\JsonApi\Schema\Resource\Transformation;
use WoohooLabs\Yin\Tests\JsonApi\Double\DummyData;
use WoohooLabs\Yin\Tests\JsonApi\Double\FakeRelationship;
use WoohooLabs\Yin\Tests\JsonApi\Double\StubRequest;
use WoohooLabs\Yin\Tests\JsonApi\Double\StubResourceTransformer;

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
            function () {
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
    public function dataNotOmittedWhenNotIncludedByDefault()
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
            ],
            $result
        );
    }

    private function createRelationship(): FakeRelationship
    {
        return new FakeRelationship();
    }
}
