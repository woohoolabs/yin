<?php
namespace WoohooLabsTest\Yin\JsonApi\Transformer;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Request\Request;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Schema\Included;
use WoohooLabs\Yin\JsonApi\Schema\Link;
use WoohooLabs\Yin\JsonApi\Schema\Links;
use WoohooLabs\Yin\JsonApi\Schema\ToOneRelationship;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractResourceTransformer;
use WoohooLabsTest\Yin\JsonApi\Utils\StubResourceTransformer;
use Zend\Diactoros\ServerRequest as DiactorosServerRequest;

class AbstractResourceTransformerTest extends PHPUnit_Framework_TestCase
{
    public function testTransformToResourceIdentifierWhenDomainObjectIsNull()
    {
        $domainObject = null;

        $transformer = $this->createTransformer();
        $transformedResourceIdentifier = $transformer->transformToResourceIdentifier($domainObject);
        $this->assertNull($transformedResourceIdentifier);
    }

    public function testTransformToResourceIdentifierWhenDomainObjectIsNotNull()
    {
        $domainObject = [];
        $type = "user";
        $id = "1";

        $transformer = $this->createTransformer($type, $id);
        $transformedResourceIdentifier = $transformer->transformToResourceIdentifier($domainObject);
        $this->assertEquals($type, $transformedResourceIdentifier["type"]);
        $this->assertEquals($id, $transformedResourceIdentifier["id"]);
        $this->assertArrayNotHasKey("meta", $transformedResourceIdentifier);
    }

    public function testTransformToResourceIdentifierWithMeta()
    {
        $domainObject = [];
        $meta = ["abc" => "def"];

        $transformer = $this->createTransformer("", "", $meta);
        $transformedResourceIdentifier = $transformer->transformToResourceIdentifier($domainObject);
        $this->assertEquals($meta, $transformedResourceIdentifier["meta"]);
    }

    public function testTransformToResourceWhenNull()
    {
        $domainObject = null;

        $transformer = $this->createTransformer();
        $transformedResource = $this->transformToResource($transformer, $domainObject);
        $this->assertNull($transformedResource);
    }

    public function testTransformToResourceWhenAlmostEmpty()
    {
        $domainObject = [];
        $type = "user";
        $id = "1";

        $transformer = $this->createTransformer($type, $id);
        $transformedResource = $this->transformToResource($transformer, $domainObject);
        $this->assertEquals($type, $transformedResource["type"]);
        $this->assertEquals($id, $transformedResource["id"]);
        $this->assertArrayNotHasKey("meta", $transformedResource);
        $this->assertArrayNotHasKey("links", $transformedResource);
        $this->assertArrayNotHasKey("attributes", $transformedResource);
        $this->assertArrayNotHasKey("relationships", $transformedResource);
    }

    public function testTransformToResourceWithMeta()
    {
        $domainObject = [];
        $meta = ["abc" => "def"];

        $transformer = $this->createTransformer("", "", $meta);
        $transformedResource = $this->transformToResource($transformer, $domainObject);
        $this->assertEquals($meta, $transformedResource["meta"]);
    }

    public function testTransformToResourceWithLinks()
    {
        $domainObject = [];
        $links = Links::createAbsoluteWithSelf(new Link("http://example.com/api/users"));

        $transformer = $this->createTransformer("", "", [], $links);
        $transformedResource = $this->transformToResource($transformer, $domainObject);
        $this->assertCount(1, $transformedResource["links"]);
        $this->assertArrayHasKey("self", $transformedResource["links"]);
    }

    public function testTransformToResourceWithAttributes()
    {
        $domainObject = [
            "name" => "John Doe",
            "age" => 50
        ];
        $attributes = [
            "full_name" => function(array $object, RequestInterface $request) use ($domainObject) {
                $this->assertEquals($object, $domainObject);
                $this->assertInstanceOf(RequestInterface::class, $request);
                return "James Bond";
            },
            "birth" => function(array $object) use ($domainObject) {
                return 2015 - $object["age"];
            }
        ];

        $transformer = $this->createTransformer("", "", [], null, $attributes);
        $transformedResource = $this->transformToResource($transformer, $domainObject);
        $this->assertEquals("James Bond", $transformedResource["attributes"]["full_name"]);
        $this->assertEquals(2015 - 50, $transformedResource["attributes"]["birth"]);
        $this->assertArrayNotHasKey("name", $transformedResource["attributes"]);
        $this->assertArrayNotHasKey("name", $transformedResource);
        $this->assertArrayNotHasKey("age", $transformedResource["attributes"]);
        $this->assertArrayNotHasKey("age", $transformedResource);
    }

    public function testTransformToResourceWithDefaultRelationship()
    {
        $domainObject = [
            "name" => "John Doe",
            "age" => 50
        ];
        $defaultRelationships = ["father"];
        $relationships = [
            "father" => function(array $object, RequestInterface $request) use ($domainObject) {
                $this->assertEquals($object, $domainObject);
                $this->assertInstanceOf(RequestInterface::class, $request);

                $relationship = new ToOneRelationship();
                $relationship->setData([], new StubResourceTransformer("user", "2"));
                return $relationship;
            }
        ];

        $included = new Included();
        $transformer = $this->createTransformer("user", "1", [], null, [], $defaultRelationships, $relationships);
        $transformedResource = $this->transformToResource($transformer, $domainObject, null, $included);
        $this->assertArrayHasKey("father", $transformedResource["relationships"]);
        $this->assertEquals("user", $transformedResource["relationships"]["father"]["data"]["type"]);
        $this->assertEquals("2", $transformedResource["relationships"]["father"]["data"]["id"]);
        $this->assertArrayNotHasKey("name", $transformedResource["relationships"]);
        $this->assertArrayNotHasKey("age", $transformedResource["relationships"]);
        $this->assertInternalType("array", $included->getResource("user", "2"));
    }

    public function testTransformToResourceWithoutIncludedRelationship()
    {
        $defaultRelationships = [];
        $relationships = [
            "father" => function() {
                $relationship = new ToOneRelationship();
                $relationship->setData([], new StubResourceTransformer("user", "2"));
                return $relationship;
            }
        ];
        $request = new Request(new DiactorosServerRequest());
        $request = $request->withQueryParams(["fields" => ["user" => ""]]);

        $included = new Included();
        $transformer = $this->createTransformer("user", "1", [], null, [], $defaultRelationships, $relationships);
        $transformedResource = $this->transformToResource($transformer, [], $request, $included);
        $this->assertArrayNotHasKey("father", $transformedResource["relationships"]);
        $this->assertNull($included->getResource("user", "2"));
    }

    /**
     * @expectedException \WoohooLabs\Yin\JsonApi\Exception\InclusionUnrecognized
     */
    public function testTransformToResourceWithInvalidRelationship()
    {
        $defaultRelationships = ["father"];
        $relationships = [
            "father" => function() {
                return new ToOneRelationship();
            }
        ];
        $request = new Request(new DiactorosServerRequest());
        $request = $request->withQueryParams(["include" => "mother"]);

        $transformer = $this->createTransformer("user", "1", [], null, [], $defaultRelationships, $relationships);
        $this->transformToResource($transformer, [], $request);
    }

    public function testTransformToResourceToRelationshipWhenEmpty()
    {
        $defaultRelationships = ["father"];
        $relationships = [];

        $request = new Request(new DiactorosServerRequest());
        $included = new Included();
        $transformer = $this->createTransformer("user", "1", [], null, [], $defaultRelationships, $relationships);
        $transformedResource = $transformer->transformRelationship([], $request, $included, "father", "");
        $this->assertNull($transformedResource);
    }

    public function testTransformToRelationship()
    {
        $defaultRelationships = ["father"];
        $relationships = [
            "father" => function() {
                $relationship = new ToOneRelationship();
                $relationship->setData([], new StubResourceTransformer("user", "2"));
                return $relationship;
            }
        ];

        $request = new Request(new DiactorosServerRequest());
        $included = new Included();
        $transformer = $this->createTransformer("user", "1", [], null, [], $defaultRelationships, $relationships);
        $transformedResource = $transformer->transformRelationship([], $request, $included, "father", "");
        $this->assertEquals("user", $transformedResource["data"]["type"]);
        $this->assertEquals("2", $transformedResource["data"]["id"]);
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractResourceTransformer $transformer
     * @param mixed $domainObject
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \WoohooLabs\Yin\JsonApi\Schema\Included $included
     * @return array|null
     */
    protected function transformToResource(
        AbstractResourceTransformer $transformer,
        $domainObject,
        RequestInterface $request = null,
        Included $included = null
    ) {
        return $transformer->transformToResource(
            $domainObject,
            $request ? $request : new Request(new DiactorosServerRequest()),
            $included ? $included : new Included(),
            ""
        );
    }

    /**
     * @param string $type
     * @param string $id
     * @param array $meta
     * @param \WoohooLabs\Yin\JsonApi\Schema\Links|null $links
     * @param array $attributes
     * @param array $defaultRelationships
     * @param array $relationships
     * @return \WoohooLabsTest\Yin\JsonApi\Utils\StubResourceTransformer
     */
    protected function createTransformer(
        $type = "",
        $id = "",
        array $meta = [],
        Links $links = null,
        array $attributes = [],
        array $defaultRelationships = [],
        array $relationships = []
    ) {
        return new StubResourceTransformer(
            $type,
            $id,
            $meta,
            $links,
            $attributes,
            $defaultRelationships,
            $relationships
        );
    }
}
