<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Schema\Relationship;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;
use WoohooLabs\Yin\JsonApi\Schema\Link\RelationshipLinks;
use WoohooLabs\Yin\JsonApi\Schema\Relationship\ToManyRelationship;
use WoohooLabs\Yin\JsonApi\Schema\Resource\ResourceInterface;
use WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformation;
use WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformer;
use WoohooLabs\Yin\Tests\JsonApi\Double\DummyData;
use WoohooLabs\Yin\Tests\JsonApi\Double\StubJsonApiRequest;
use WoohooLabs\Yin\Tests\JsonApi\Double\StubResource;

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
