<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Schema\Relationship;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;
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
    public function setData()
    {
        $relationship = $this->createRelationship();

        $relationship->setData(["id" => 1], new StubResource());
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
            new StubResource()
        );
        $data = $relationship->getRetrieveData();

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

        $this->assertEquals([], $relationshipObject);
    }

    private function createRelationship(): FakeRelationship
    {
        return new FakeRelationship();
    }
}
