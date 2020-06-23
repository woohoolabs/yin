<?php

declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Hydrator;

use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Stream;
use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;
use WoohooLabs\Yin\JsonApi\Exception\RelationshipTypeInappropriate;
use WoohooLabs\Yin\JsonApi\Exception\ResourceTypeMissing;
use WoohooLabs\Yin\JsonApi\Exception\ResourceTypeUnacceptable;
use WoohooLabs\Yin\JsonApi\Hydrator\AbstractHydrator;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToManyRelationship;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToOneRelationship;
use WoohooLabs\Yin\JsonApi\Request\JsonApiRequest;
use WoohooLabs\Yin\JsonApi\Serializer\JsonDeserializer;
use WoohooLabs\Yin\Tests\JsonApi\Double\StubHydrator;

use function json_encode;

class AbstractHydratorTest extends TestCase
{
    /**
     * @test
     */
    public function validateTypeWhenMissing(): void
    {
        $body = [
            "data" => [],
        ];

        $hydrator = $this->createHydrator();

        $this->expectException(ResourceTypeMissing::class);
        $hydrator->hydrateForCreate($this->createRequest($body), new DefaultExceptionFactory(), []);
    }

    /**
     * @test
     */
    public function validateTypeWhenUnacceptableAndOnlyOneAcceptable(): void
    {
        $body = [
            "data" => [
                "type" => "elephant",
            ],
        ];

        $hydrator = $this->createHydrator(["fox"]);

        $this->expectException(ResourceTypeUnacceptable::class);
        $hydrator->hydrateForCreate($this->createRequest($body), new DefaultExceptionFactory(), []);
    }

    /**
     * @test
     */
    public function validateTypeWhenUnacceptableAndMoreAcceptable(): void
    {
        $body = [
            "data" => [
                "type" => "elephant",
            ],
        ];

        $hydrator = $this->createHydrator(["fox", "wolf"]);

        $this->expectException(ResourceTypeUnacceptable::class);
        $hydrator->hydrateForUpdate($this->createRequest($body), new DefaultExceptionFactory(), []);
    }

    /**
     * @test
     */
    public function hydrateAttributesWhenEmpty(): void
    {
        $body = [
            "data" => [
                "type" => "elephant",
                "id" => "1",
            ],
        ];

        $hydrator = $this->createHydrator(["elephant"]);
        $domainObject = $hydrator->hydrateForUpdate($this->createRequest($body), new DefaultExceptionFactory(), []);
        $this->assertEquals([], $domainObject);
    }

    /**
     * @test
     */
    public function hydrateAttributesWhenNull(): void
    {
        $body = [
            "data" => [
                "type" => "elephant",
                "id" => "1",
                "attributes" => [
                    "height" => null,
                ],
            ],
        ];
        $attributeHydrator = [
            "height" => function (array &$elephant, $attribute): void {
                $elephant["height"] = $attribute;
            },
        ];

        $hydrator = $this->createHydrator(["elephant"], $attributeHydrator);
        $domainObject = $hydrator->hydrateForUpdate($this->createRequest($body), new DefaultExceptionFactory(), []);
        $this->assertEquals(['height' => null], $domainObject);
    }

    /**
     * @test
     */
    public function hydrateAttributesWhenHydratorEmpty(): void
    {
        $body = [
            "data" => [
                "type" => "elephant",
                "id" => "1",
                "attributes" => [
                    "height" => 2.5,
                ],
            ],
        ];
        $attributeHydrator = [
            "weight" => function (array &$elephant, $attribute): void {
                $elephant["weight"] = $attribute;
            },
        ];

        $hydrator = $this->createHydrator(["elephant"], $attributeHydrator);
        $domainObject = $hydrator->hydrateForUpdate($this->createRequest($body), new DefaultExceptionFactory(), []);
        $this->assertEquals([], $domainObject);
    }

    /**
     * @test
     */
    public function hydrateAttributesWhenHydratorReturnByReference(): void
    {
        $weight = 1000;
        $body = [
            "data" => [
                "type" => "elephant",
                "id" => "1",
                "attributes" => [
                    "weight" => $weight,
                ],
            ],
        ];
        $attributeHydrator = [
            "weight" => function (array &$elephant, $attribute): void {
                $elephant["weight"] = $attribute;
            },
        ];

        $hydrator = $this->createHydrator(["elephant"], $attributeHydrator);
        $domainObject = $hydrator->hydrateForUpdate($this->createRequest($body), new DefaultExceptionFactory(), []);
        $this->assertEquals(["weight" => $weight], $domainObject);
    }

    /**
     * @test
     */
    public function hydrateAttributesWhenHydratorReturnByValue(): void
    {
        $weight = 1000;
        $body = [
            "data" => [
                "type" => "elephant",
                "id" => "1",
                "attributes" => [
                    "weight" => $weight,
                ],
            ],
        ];
        $attributeHydrator = [
            "weight" => function (array $elephant, $attribute): array {
                $elephant["weight"] = $attribute;
                return $elephant;
            },
        ];

        $hydrator = $this->createHydrator(["elephant"], $attributeHydrator);
        $domainObject = $hydrator->hydrateForUpdate($this->createRequest($body), new DefaultExceptionFactory(), []);
        $this->assertEquals(["weight" => $weight], $domainObject);
    }

    /**
     * @test
     */
    public function hydrateRelationshipsWhenHydratorEmpty(): void
    {
        $body = [
            "data" => [
                "type" => "elephant",
                "id" => "1",
                "relationships" => [
                    "parents" => [],
                ],
            ],
        ];
        $relationshipHydrator = [
            "children" => function (array &$elephant, ToManyRelationship $children): void {
                $elephant["children"] = ["Dumbo", "Mambo"];
            },
        ];

        $hydrator = $this->createHydrator(["elephant"], [], $relationshipHydrator);
        $domainObject = $hydrator->hydrateForUpdate($this->createRequest($body), new DefaultExceptionFactory(), []);
        $this->assertEquals([], $domainObject);
    }

    /**
     * @test
     */
    public function hydrateRelationshipsWhenCardinalityInappropriate(): void
    {
        $body = [
            "data" => [
                "type" => "elephant",
                "id" => "1",
                "relationships" => [
                    "children" => [
                        "data" => [
                            "type" => "elephant",
                            "id" => "2",
                        ],
                    ],
                ],
            ],
        ];
        $relationshipHydrator = [
            "children" => function (array &$elephant, ToManyRelationship $children): void {
                $elephant["children"] = $children->getResourceIdentifiers();
            },
        ];
        $hydrator = $this->createHydrator(["elephant"], [], $relationshipHydrator);

        $this->expectException(RelationshipTypeInappropriate::class);
        $hydrator->hydrateForUpdate($this->createRequest($body), new DefaultExceptionFactory(), []);
    }

    /**
     * @test
     */
    public function hydrateRelationshipsWhenCardinalityInappropriate2(): void
    {
        $body = [
            "data" => [
                "type" => "elephant",
                "id" => "1",
                "relationships" => [
                    "children" => [
                        "data" => [
                            [
                                "type" => "elephant",
                                "id" => "2",
                            ],
                        ],
                    ],
                ],
            ],
        ];
        $relationshipHydrator = [
            "children" => function (array &$elephant, ToOneRelationship $children): void {
                $elephant["children"] = $children->getResourceIdentifier();
            },
        ];
        $hydrator = $this->createHydrator(["elephant"], [], $relationshipHydrator);

        $this->expectException(RelationshipTypeInappropriate::class);
        $hydrator->hydrateForUpdate($this->createRequest($body), new DefaultExceptionFactory(), []);
    }

    /**
     * @test
     */
    public function hydrateRelationshipsWhenExpectedCardinalityIsNotSet(): void
    {
        $body = [
            "data" => [
                "type" => "elephant",
                "id" => "1",
                "relationships" => [
                    "children" => [
                        "data" => [
                            [
                                "type" => "elephant",
                                "id" => "2",
                            ],
                        ],
                    ],
                ],
            ],
        ];
        $relationshipHydrator = [
            "children" => function (array &$elephant, $children): void {
                $elephant["children"] = "Dumbo";
            },
        ];

        $hydrator = $this->createHydrator(["elephant"], [], $relationshipHydrator);
        $domainObject = $hydrator->hydrateForUpdate($this->createRequest($body), new DefaultExceptionFactory(), []);
        $this->assertEquals(["children" => "Dumbo"], $domainObject);
    }

    /**
     * @test
     */
    public function hydrateRelationships(): void
    {
        $body = [
            "data" => [
                "type" => "elephant",
                "id" => "1",
                "relationships" => [
                    "owner" => [
                        "data" => [
                            "type" => "person",
                            "id" => "1",
                        ],
                    ],
                    "children" => [
                        "data" => [
                            [
                                "type" => "elephant",
                                "id" => "2",
                            ],
                        ],
                    ],
                ],
            ],
        ];
        $relationshipHydrator = [
            "owner" => function (array $elephant, ToOneRelationship $owner): array {
                $resourceIdentifier = $owner->getResourceIdentifier();

                $elephant["owner"] = $resourceIdentifier !== null ? $resourceIdentifier->getId() : "";
                return $elephant;
            },
            "children" => function (array &$elephant, ToManyRelationship $children): void {
                $elephant["children"] = $children->getResourceIdentifierIds();
            },
        ];

        $hydrator = $this->createHydrator(["elephant"], [], $relationshipHydrator);
        $domainObject = $hydrator->hydrateForUpdate($this->createRequest($body), new DefaultExceptionFactory(), []);
        $this->assertEquals(["owner" => "1", "children" => ["2"]], $domainObject);
    }

    private function createRequest(array $body): JsonApiRequest
    {
        $data = json_encode($body);
        if ($data === false) {
            $data = "";
        }

        $psrRequest = new ServerRequest();
        $psrRequest = $psrRequest
            ->withParsedBody($body)
            ->withBody(new Stream("php://memory", "rw"));
        $psrRequest->getBody()->write($data);

        return new JsonApiRequest($psrRequest, new DefaultExceptionFactory(), new JsonDeserializer());
    }

    private function createHydrator(
        array $acceptedTypes = [],
        array $attributeHydrator = [],
        array $relationshipHydrator = []
    ): AbstractHydrator {
        return new StubHydrator($acceptedTypes, $attributeHydrator, $relationshipHydrator);
    }
}
