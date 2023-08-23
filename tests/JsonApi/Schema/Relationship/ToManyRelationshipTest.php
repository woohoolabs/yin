<?php

declare(strict_types=1);

namespace Devleand\Yin\Tests\JsonApi\Schema\Relationship;

use PHPUnit\Framework\TestCase;
use Devleand\Yin\JsonApi\Exception\DefaultExceptionFactory;
use Devleand\Yin\JsonApi\Schema\Link\RelationshipLinks;
use Devleand\Yin\JsonApi\Schema\Relationship\ToManyRelationship;
use Devleand\Yin\JsonApi\Schema\Resource\ResourceInterface;
use Devleand\Yin\JsonApi\Transformer\ResourceTransformation;
use Devleand\Yin\JsonApi\Transformer\ResourceTransformer;
use Devleand\Yin\Tests\JsonApi\Double\DummyData;
use Devleand\Yin\Tests\JsonApi\Double\StubJsonApiRequest;
use Devleand\Yin\Tests\JsonApi\Double\StubResource;

class ToManyRelationshipTest extends TestCase
{
    /**
     * @test
     */
    public function transformEmpty(): void
    {
        $transformation = new ResourceTransformation(
            new StubResource(),
            [],
            "",
            new StubJsonApiRequest(),
            "",
            "",
            "",
            new DefaultExceptionFactory()
        );
        $relationship = $this->createRelationship([], null, [], $transformation->resource);

        $relationshipObject = $relationship->transform(
            $transformation,
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
    public function transform(): void
    {
        $relationship = $this->createRelationship(
            [],
            null,
            [[], []],
            new StubResource("abc", "1")
        );

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
                "data" => [
                    [
                        "type" => "abc",
                        "id" => "1",
                    ],
                    [
                        "type" => "abc",
                        "id" => "1",
                    ],
                ],
            ],
            $relationshipObject
        );
    }

    private function createRelationship(
        array $meta = [],
        ?RelationshipLinks $links = null,
        array $data = [],
        ?ResourceInterface $resource = null
    ): ToManyRelationship {
        return new ToManyRelationship($meta, $links, $data, $resource);
    }
}
