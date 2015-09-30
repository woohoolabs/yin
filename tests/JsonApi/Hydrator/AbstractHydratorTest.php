<?php
namespace WoohooLabsTest\Yin\JsonApi\Hydrator;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactory;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToManyRelationship;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToOneRelationship;
use WoohooLabs\Yin\JsonApi\Request\Request;
use WoohooLabsTest\Yin\JsonApi\Utils\StubHydrator;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Stream;

class AbstractHydratorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \WoohooLabs\Yin\JsonApi\Exception\ResourceTypeMissing
     */
    public function testValidateTypeWhenMissing()
    {
        $body = [
            "data" => []
        ];

        $hydrator = $this->createHydrator();
        $hydrator->hydrateForCreate($this->createRequest($body), new ExceptionFactory(), []);
    }

    /**
     * @expectedException \WoohooLabs\Yin\JsonApi\Exception\ResourceTypeUnacceptable
     */
    public function testValidateTypeWhenUnacceptableAndOnlyOneAcceptable()
    {
        $body = [
            "data" => [
                "type" => "elephant"
            ]
        ];

        $hydrator = $this->createHydrator("fox");
        $hydrator->hydrateForCreate($this->createRequest($body), new ExceptionFactory(), []);
    }

    /**
     * @expectedException \WoohooLabs\Yin\JsonApi\Exception\ResourceTypeUnacceptable
     */
    public function testValidateTypeWhenUnacceptableAndMoreAcceptable()
    {
        $body = [
            "data" => [
                "type" => "elephant"
            ]
        ];

        $hydrator = $this->createHydrator(["fox", "wolf"]);
        $hydrator->hydrateForUpdate($this->createRequest($body), new ExceptionFactory(), []);
    }

    public function testHydrateAttributesWhenEmpty()
    {
        $body = [
            "data" => [
                "type" => "elephant",
                "id" => "1"
            ]
        ];

        $hydrator = $this->createHydrator("elephant");
        $domainObject = $hydrator->hydrateForUpdate($this->createRequest($body), new ExceptionFactory(), []);
        $this->assertEquals([], $domainObject);
    }

    public function testHydrateAttributesWhenHydratorEmpty()
    {
        $body = [
            "data" => [
                "type" => "elephant",
                "id" => "1",
                "attributes" => [
                    "height" => 2.5
                ]
            ]
        ];
        $attributeHydrator = [
            "weight" => function (array &$elephant, $attribute) {
                $elephant["weight"] = $attribute;
            }
        ];

        $hydrator = $this->createHydrator("elephant", $attributeHydrator);
        $domainObject = $hydrator->hydrateForUpdate($this->createRequest($body), new ExceptionFactory(), []);
        $this->assertEquals([], $domainObject);
    }

    public function testHydrateAttributesWhenHydratorReturnByReference()
    {
        $weight = 1000;
        $body = [
            "data" => [
                "type" => "elephant",
                "id" => "1",
                "attributes" => [
                    "weight" => $weight
                ]
            ]
        ];
        $attributeHydrator = [
            "weight" => function (array &$elephant, $attribute) {
                $elephant["weight"] = $attribute;
            }
        ];

        $hydrator = $this->createHydrator("elephant", $attributeHydrator);
        $domainObject = $hydrator->hydrateForUpdate($this->createRequest($body), new ExceptionFactory(), []);
        $this->assertEquals(["weight" => $weight], $domainObject);
    }

    public function testHydrateAttributesWhenHydratorReturnByValue()
    {
        $weight = 1000;
        $body = [
            "data" => [
                "type" => "elephant",
                "id" => "1",
                "attributes" => [
                    "weight" => $weight
                ]
            ]
        ];
        $attributeHydrator = [
            "weight" => function (array $elephant, $attribute) {
                $elephant["weight"] = $attribute;
                return $elephant;
            }
        ];

        $hydrator = $this->createHydrator("elephant", $attributeHydrator);
        $domainObject = $hydrator->hydrateForUpdate($this->createRequest($body), new ExceptionFactory(), []);
        $this->assertEquals(["weight" => $weight], $domainObject);
    }

    public function testHydrateRelationshipsWhenHydratorEmpty()
    {
        $body = [
            "data" => [
                "type" => "elephant",
                "id" => "1",
                "relationships" => [
                    "parents" => []
                ]
            ]
        ];
        $relationshipHydrator = [
            "children" => function (array &$elephant, ToManyRelationship $children) {
                $elephant["children"] = ["Dumbo", "Mambo"];
            }
        ];

        $hydrator = $this->createHydrator("elephant", [], $relationshipHydrator);
        $domainObject = $hydrator->hydrateForUpdate($this->createRequest($body), new ExceptionFactory(), []);
        $this->assertEquals([], $domainObject);
    }

    /**
     * @expectedException \WoohooLabs\Yin\JsonApi\Exception\RelationshipTypeInappropriate
     */
    public function testHydrateRelationshipsWhenCardinalityInappropriate()
    {
        $body = [
            "data" => [
                "type" => "elephant",
                "id" => "1",
                "relationships" => [
                    "children" => [
                        "data" => [
                            "type" => "elephant",
                            "id" => "2"
                        ]
                    ]
                ]
            ]
        ];
        $relationshipHydrator = [
            "children" => function (array &$elephant, ToManyRelationship $children) {
                $elephant["children"] = $children->getResourceIdentifiers();
            }
        ];

        $hydrator = $this->createHydrator("elephant", [], $relationshipHydrator);
        $hydrator->hydrateForUpdate($this->createRequest($body), new ExceptionFactory(), []);
    }

    /**
     * @expectedException \WoohooLabs\Yin\JsonApi\Exception\RelationshipTypeInappropriate
     */
    public function testHydrateRelationshipsWhenCardinalityInappropriate2()
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
                                "id" => "2"
                            ]
                        ]
                    ]
                ]
            ]
        ];
        $relationshipHydrator = [
            "children" => function (array &$elephant, ToOneRelationship $children) {
                $elephant["children"] = $children->getResourceIdentifier();
            }
        ];

        $hydrator = $this->createHydrator("elephant", [], $relationshipHydrator);
        $hydrator->hydrateForUpdate($this->createRequest($body), new ExceptionFactory(), []);
    }

    public function testHydrateRelationshipsWhenExpectedCardinalityIsNotSet()
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
                                "id" => "2"
                            ]
                        ]
                    ]
                ]
            ]
        ];
        $relationshipHydrator = [
            "children" => function (array &$elephant, $children) {
                $elephant["children"] = "Dumbo";
            }
        ];

        $hydrator = $this->createHydrator("elephant", [], $relationshipHydrator);
        $domainObject = $hydrator->hydrateForUpdate($this->createRequest($body), new ExceptionFactory(), []);
        $this->assertEquals(["children" => "Dumbo"], $domainObject);
    }

    public function testHydrateRelationships()
    {
        $body = [
            "data" => [
                "type" => "elephant",
                "id" => "1",
                "relationships" => [
                    "owner" => [
                        "data" => [
                            "type" => "person",
                            "id" => "1"
                        ]
                    ],
                    "children" => [
                        "data" => [
                            [
                                "type" => "elephant",
                                "id" => "2"
                            ]
                        ]
                    ]
                ]
            ]
        ];
        $relationshipHydrator = [
            "owner" => function (array $elephant, ToOneRelationship $owner) {
                $elephant["owner"] = $owner->getResourceIdentifier()->getId();
                return $elephant;
            },
            "children" => function (array &$elephant, ToManyRelationship $children) {
                $elephant["children"] = $children->getResourceIdentifierIds();
            }
        ];

        $hydrator = $this->createHydrator("elephant", [], $relationshipHydrator);
        $domainObject = $hydrator->hydrateForUpdate($this->createRequest($body), new ExceptionFactory(), []);
        $this->assertEquals(["owner" => "1", "children" => ["2"]], $domainObject);
    }

    private function createRequest(array $body)
    {
        $psrRequest = new ServerRequest();
        $psrRequest = $psrRequest
            ->withParsedBody($body)
            ->withBody(new Stream("php://memory", "rw"));
        $psrRequest->getBody()->write(json_encode($body));

        $request = new Request($psrRequest);

        return $request;
    }

    /**
     * @param string|array $acceptedType
     * @param array $attributeHydrator
     * @param array $relationshipHydrator
     * @return \WoohooLabs\Yin\JsonApi\Hydrator\AbstractHydrator
     */
    private function createHydrator($acceptedType = "", array $attributeHydrator = [], array $relationshipHydrator = [])
    {
        return new StubHydrator($acceptedType, $attributeHydrator, $relationshipHydrator);
    }
}
