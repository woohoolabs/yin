<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Transformer;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;
use WoohooLabs\Yin\JsonApi\Exception\InclusionUnrecognized;
use WoohooLabs\Yin\JsonApi\Request\Request;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Schema\Link\ResourceLinks;
use WoohooLabs\Yin\JsonApi\Schema\Relationship\ToOneRelationship;
use WoohooLabs\Yin\JsonApi\Schema\Resource\ResourceInterface;
use WoohooLabs\Yin\JsonApi\Serializer\JsonDeserializer;
use WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformation;
use WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformer;
use WoohooLabs\Yin\Tests\JsonApi\Double\DummyData;
use WoohooLabs\Yin\Tests\JsonApi\Double\StubRequest;
use WoohooLabs\Yin\Tests\JsonApi\Double\StubResource;
use Zend\Diactoros\ServerRequest as DiactorosServerRequest;

class ResourceTransformerTest extends TestCase
{
    /**
     * @test
     */
    public function transformToResourceIdentifierWhenObjectIsNull()
    {
        $resource = $this->createResource();

        $resourceIdentifier = $this->toResourceIdentifier($resource, null);

        $this->assertNull($resourceIdentifier);
    }

    /**
     * @test
     */
    public function transformToResourceIdentifierWhenObjectIsNotNull()
    {
        $resource = $this->createResource("user", "1");

        $resourceIdentifier = $this->toResourceIdentifier($resource, []);

        $this->assertEquals(
            [
                "type" => "user",
                "id" => "1",
            ],
            $resourceIdentifier
        );
    }

    /**
     * @test
     */
    public function transformToResourceIdentifierWithMeta()
    {
        $resource = $this->createResource("user", "1", ["abc" => "def"]);

        $resourceIdentifier = $this->toResourceIdentifier($resource, []);

        $this->assertEquals(
            [
                "type" => "user",
                "id" => "1",
                "meta" => ["abc" => "def"],
            ],
            $resourceIdentifier
        );
    }

    /**
     * @test
     */
    public function transformToResourceObjectWhenNull()
    {
        $resource = $this->createResource("user", "1");

        $resourceObject = $this->toResourceObject($resource, null);

        $this->assertNull($resourceObject);
    }

    /**
     * @test
     */
    public function transformToResourceWhenAlmostEmpty()
    {
        $resource = $this->createResource("user", "1");

        $resourceObject = $this->toResourceObject($resource, []);

        $this->assertEquals(
            [
                "type" => "user",
                "id" => "1",
            ],
            $resourceObject
        );
    }

    /**
     * @test
     */
    public function transformToResourceWithMeta()
    {
        $resource = $this->createResource("", "", ["abc" => "def"]);

        $resourceObject = $this->toResourceObject($resource, []);

        $this->assertEquals(
            [
                "type" => "",
                "id" => "",
                "meta" => ["abc" => "def"],
            ],
            $resourceObject
        );
    }

    /**
     * @test
     */
    public function transformToResourceWithLinks()
    {
        $resource = $this->createResource("", "", [], new ResourceLinks());

        $resourceObject = $this->toResourceObject($resource, []);

        $this->assertEquals(
            [
                "type" => "",
                "id" => "",
                "links" => [],
            ],
            $resourceObject
        );
    }

    /**
     * @test
     */
    public function transformToResourceWithMetaAndLinks()
    {
        $resource = $this->createResource("user", "1", ["abc" => "def"], new ResourceLinks());

        $resourceObject = $this->toResourceObject($resource, []);

        $this->assertEquals(
            [
                "type" => "user",
                "id" => "1",
                "meta" => ["abc" => "def"],
                "links" => [],
            ],
            $resourceObject
        );
    }

    /**
     * @test
     */
    public function transformToResourceWithAttributes()
    {
        $resource = $this->createResource(
            "user",
            "1",
            ["abc" => "def"],
            new ResourceLinks(),
            [
                "full_name" => function (array $object, RequestInterface $request) {
                    return $object["name"];
                },
                "birth" => function (array $object) {
                    return 2015 - $object["age"];
                },
            ]
        );

        $resourceObject = $this->toResourceObject(
            $resource,
            [
                "name" => "John Doe",
                "age" => "30",
            ]
        );

        $this->assertEquals(
            [
                "type" => "user",
                "id" => "1",
                "meta" => ["abc" => "def"],
                "links" => [],
                "attributes" => [
                    "full_name" => "John Doe",
                    "birth" => 1985,
                ],
            ],
            $resourceObject
        );
    }

    /**
     * @test
     */
    public function transformToResourceWithDefaultRelationship()
    {
        $resource = $this->createResource(
            "user",
            "1",
            [],
            null,
            [],
            ["father"],
            [
                "father" => function (array $object, RequestInterface $request) {
                    return ToOneRelationship::create()
                        ->setData([""], new StubResource("user", "2"));
                },
            ]
        );

        $resourceObject = $this->toResourceObject($resource, []);

        $this->assertEquals(
            [
                "type" => "user",
                "id" => "1",
                "relationships" => [
                    "father" => [
                        "data" => [
                            "type" => "user",
                            "id" => "2",
                        ],
                    ],
                ],
            ],
            $resourceObject
        );
    }

    /**
     * @test
     */
    public function transformToResourceWithoutIncludedRelationship()
    {
        $resource = $this->createResource(
            "user",
            "1",
            [],
            null,
            [],
            [],
            [
                "father" => function (array $object, RequestInterface $request) {
                    return ToOneRelationship::create()
                        ->setData([], new StubResource("user", "2"));
                },
            ]
        );

        $resourceObject = $this->toResourceObject($resource, []);

        $this->assertEquals(
            [
                "type" => "user",
                "id" => "1",
            ],
            $resourceObject
        );
    }

    /**
     * @test
     */
    public function transformToResourceWithInvalidRelationship()
    {
        $resource = $this->createResource(
            "user",
            "1",
            [],
            null,
            [],
            ["father"],
            [
                "father" => function (array $object, RequestInterface $request) {
                    return ToOneRelationship::create();
                },
            ]
        );

        $this->expectException(InclusionUnrecognized::class);

        $this->toResourceObject($resource, [], StubRequest::create()->withQueryParams(["include" => "mother"]));
    }

    /**
     * @test
     */
    public function transformToResourceToRelationshipWhenEmpty()
    {
        $resource = $this->createResource(
            "user",
            "1",
            [],
            null,
            [],
            [],
            []
        );

        $resourceObject = $this->toRelationshipObject($resource, [], null, "father");

        $this->assertNull($resourceObject);
    }

    /**
     * @test
     */
    public function transformToRelationship()
    {
        $resource = $this->createResource(
            "user",
            "1",
            [],
            null,
            [],
            [],
            [
                "father" => function () {
                    $relationship = new ToOneRelationship();
                    $relationship->setData(["Father Vader"], new StubResource("user", "2"));
                    return $relationship;
                }
            ]
        );

        $resourceObject = $this->toRelationshipObject($resource, [], null, "father");

        $this->assertEquals(
            [
                "data" => [
                    "type" => "user",
                    "id" => "2",
                ],
            ],
            $resourceObject
        );
    }

    /**
     * @param mixed $object
     */
    private function toResourceIdentifier(
        ResourceInterface $resource,
        $object,
        ?RequestInterface $request = null
    ): ?array {
        $transformation = new ResourceTransformation(
            $resource,
            $object,
            "",
            $request ? $request : new Request(
                new DiactorosServerRequest(),
                new DefaultExceptionFactory(),
                new JsonDeserializer()
            ),
            "",
            "",
            "",
            new DefaultExceptionFactory()
        );

        $transformer = new ResourceTransformer();

        return $transformer->transformToResourceIdentifier($transformation);
    }

    /**
     * @param mixed $object
     */
    private function toResourceObject(
        ResourceInterface $resource,
        $object,
        ?RequestInterface $request = null
    ): ?array {
        $transformation = new ResourceTransformation(
            $resource,
            $object,
            "",
            $request ? $request : new Request(
                new DiactorosServerRequest(),
                new DefaultExceptionFactory(),
                new JsonDeserializer()
            ),
            "",
            "",
            "",
            new DefaultExceptionFactory()
        );

        $transformer = new ResourceTransformer();

        return $transformer->transformToResourceObject($transformation, new DummyData());
    }

    /**
     * @param mixed $object
     */
    private function toRelationshipObject(
        ResourceInterface $resource,
        $object,
        ?RequestInterface $request = null,
        string $requestedRelationshipName = ""
    ): ?array {
        $transformation = new ResourceTransformation(
            $resource,
            $object,
            "",
            $request ? $request : new Request(
                new DiactorosServerRequest(),
                new DefaultExceptionFactory(),
                new JsonDeserializer()
            ),
            "",
            $requestedRelationshipName,
            $requestedRelationshipName,
            new DefaultExceptionFactory()
        );

        $transformer = new ResourceTransformer();

        return $transformer->transformToRelationshipObject($transformation, new DummyData());
    }

    private function createResource(
        string $type = "",
        string $id = "",
        array $meta = [],
        ?ResourceLinks $links = null,
        array $attributes = [],
        array $defaultRelationships = [],
        array $relationships = []
    ): StubResource {
        return new StubResource($type, $id, $meta, $links, $attributes, $defaultRelationships, $relationships);
    }

    protected function createResourceTransformer(): ResourceTransformer
    {
        return new ResourceTransformer();
    }
}
