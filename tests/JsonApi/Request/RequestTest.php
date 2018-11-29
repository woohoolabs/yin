<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Request;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;
use WoohooLabs\Yin\JsonApi\Exception\MediaTypeUnacceptable;
use WoohooLabs\Yin\JsonApi\Exception\MediaTypeUnsupported;
use WoohooLabs\Yin\JsonApi\Exception\QueryParamMalformed;
use WoohooLabs\Yin\JsonApi\Exception\QueryParamUnrecognized;
use WoohooLabs\Yin\JsonApi\Exception\RelationshipNotExists;
use WoohooLabs\Yin\JsonApi\Request\JsonApiRequest;
use WoohooLabs\Yin\JsonApi\Serializer\JsonDeserializer;
use Zend\Diactoros\ServerRequest;

class RequestTest extends TestCase
{
    /**
     * @test
     */
    public function validateJsonApiContentTypeHeader()
    {
        $request = $this->createRequestWithHeader("content-type", "application/vnd.api+json");

        $request->validateContentTypeHeader();

        $this->addToAssertionCount(1);
    }

    /**
     * @test
     */
    public function validateJsonApiContentTypeHeaderWithSemicolon()
    {
        $request = $this->createRequestWithHeader("content-type", "application/vnd.api+json;");

        $request->validateContentTypeHeader();

        $this->addToAssertionCount(1);
    }

    /**
     * @test
     */
    public function validateEmptyContentTypeHeader()
    {
        $request = $this->createRequestWithHeader("content-type", "");

        $request->validateContentTypeHeader();

        $this->addToAssertionCount(1);
    }

    /**
     * @test
     */
    public function validateHtmlContentTypeHeader()
    {
        $request = $this->createRequestWithHeader("content-type", "text/html; charset=utf-8");

        $request->validateContentTypeHeader();

        $this->addToAssertionCount(1);
    }

    /**
     * @test
     */
    public function validateMultipleMediaTypeContentTypeHeader()
    {
        $request = $this->createRequestWithHeader("content-type", "application/vnd.api+json, text/*;q=0.3, text/html;q=0.7");

        $request->validateContentTypeHeader();

        $this->addToAssertionCount(1);
    }

    /**
     * @test
     */
    public function validateCaseInsensitiveContentTypeHeader()
    {
        $request = $this->createRequestWithHeader("content-type", "Application/vnd.Api+JSON, text/*;q=0.3, text/html;Q=0.7");

        $request->validateContentTypeHeader();

        $this->addToAssertionCount(1);
    }

    /**
     * @test
     */
    public function validateInvalidContentTypeHeaderWithExtMediaType()
    {
        $request = $this->createRequestWithHeader("content-type", 'application/vnd.api+json; ext="ext1,ext2"');

        $this->expectException(MediaTypeUnsupported::class);

        $request->validateContentTypeHeader();
    }

    /**
     * @test
     */
    public function validateInvalidContentTypeHeaderWithWhitespaceBeforeParameter()
    {
        $request = $this->createRequestWithHeader("content-type", 'application/vnd.api+json ; ext="ext1,ext2"');

        $this->expectException(MediaTypeUnsupported::class);

        $request->validateContentTypeHeader();
    }

    /**
     * @test
     */
    public function validateContentTypeHeaderWithJsonApiProfileMediaTypeParameter()
    {
        $request = $this->createRequestWithHeader(
            "content-type",
            "application/vnd.api+json;profile=https://example.com/extensions/last-modified"
        );

        $request->validateContentTypeHeader();

        $this->addToAssertionCount(1);
    }

    /**
     * @test
     */
    public function validateContentTypeHeaderWithInvalidMediaTypeParameter()
    {
        $request = $this->createRequestWithHeader("content-type", "application/vnd.api+json; Charset=utf-8");

        $this->expectException(MediaTypeUnsupported::class);

        $request->validateContentTypeHeader();
    }

    /**
     * @test
     */
    public function validateAcceptHeaderWithJsonApiMediaType()
    {
        $request = $this->createRequestWithHeader("accept", "application/vnd.api+json");

        $request->validateAcceptHeader();

        $this->addToAssertionCount(1);
    }

    /**
     * @test
     */
    public function validateAcceptHeaderWithJsonApiProfileMediaTypeParameter()
    {
        $request = $this->createRequestWithHeader(
            "content-type",
            "application/vnd.api+json; Profile = https://example.com/extensions/last-modified"
        );

        $request->validateContentTypeHeader();

        $this->addToAssertionCount(1);
    }

    /**
     * @test
     */
    public function validateAcceptHeaderWithInvalidMediaTypeParameters()
    {
        $request = $this->createRequestWithHeader("accept", 'application/vnd.api+json; ext="ext1,ext2"; charset=utf-8; lang=en');

        $this->expectException(MediaTypeUnacceptable::class);

        $request->validateAcceptHeader();
    }

    /**
     * @test
     */
    public function validateEmptyQueryParams()
    {
        $request = $this->createRequestWithQueryParams([]);

        $request->validateQueryParams();

        $this->addToAssertionCount(1);
    }

    /**
     * @test
     */
    public function validateBasicQueryParams()
    {
        $request = $this->createRequestWithQueryParams(
            [
                "fields" => ["user" => "name, address"],
                "include" => ["contacts"],
                "sort" => ["-name"],
                "page" => ["number" => "1"],
                "filter" => ["age" => "21"],
                "profile" => "",
            ]
        );

        $request->validateQueryParams();

        $this->addToAssertionCount(1);
    }

    /**
     * @test
     */
    public function validateInvalidQueryParams()
    {
        $request = $this->createRequestWithQueryParams(
            [
                "fields" => ["user" => "name, address"],
                "paginate" => ["-name"],
            ]
        );

        $this->expectException(QueryParamUnrecognized::class);

        $request->validateQueryParams();
    }

    /**
     * @test
     */
    public function getIncludedFieldsWhenEmpty()
    {
        $request = $this->createRequestWithQueryParams([]);

        $includedFields = $request->getIncludedFields("");

        $this->assertEquals([], $includedFields);
    }

    /**
     * @test
     */
    public function getIncludedFieldsForResource()
    {
        $request = $this->createRequestWithQueryParams(
            [
                "fields" => [
                    "book" => "title,pages",
                ],
            ]
        );

        $includedFields = $request->getIncludedFields("book");

        $this->assertEquals(["title", "pages"], $includedFields);
    }

    /**
     * @test
     */
    public function getIncludedFieldsForUnspecifiedResource()
    {
        $request = $this->createRequestWithQueryParams(
            [
                "fields" => [
                    "book" => "title,pages",
                ],
            ]
        );

        $includedFields = $request->getIncludedFields("newspaper");

        $this->assertEquals([], $includedFields);
    }

    /**
     * @test
     */
    public function getIncludedFieldWhenMalformed()
    {
        $request = $this->createRequestWithQueryParams(
            [
                "fields" => "",
            ]
        );

        $this->expectException(QueryParamMalformed::class);

        $request->getIncludedFields("");
    }

    /**
     * @test
     */
    public function getIncludedFieldWhenFieldMalformed()
    {
        $request = $this->createRequestWithQueryParams(
            [
                "fields" => [
                    "book" => [],
                ],
            ]
        );

        $this->expectException(QueryParamMalformed::class);

        $request->getIncludedFields("");
    }

    /**
     * @test
     */
    public function isIncludedFieldWhenAllFieldsRequested()
    {
        $request = $this->createRequestWithQueryParams(["fields" => []]);
        $this->assertTrue($request->isIncludedField("book", "title"));

        $request = $this->createRequestWithQueryParams([]);
        $this->assertTrue($request->isIncludedField("book", "title"));
    }

    /**
     * @test
     */
    public function isIncludedFieldWhenNoFieldRequested()
    {
        $request = $this->createRequestWithQueryParams(["fields" => ["book1" => ""]]);

        $isIncludedField = $request->isIncludedField("book1", "title");

        $this->assertFalse($isIncludedField);
    }

    /**
     * @test
     */
    public function isIncludedFieldWhenGivenFieldIsSpecified()
    {
        $request = $this->createRequestWithQueryParams(["fields" => ["book" => "title,pages"]]);

        $isIncludedField = $request->isIncludedField("book", "title");

        $this->assertTrue($isIncludedField);
    }

    /**
     * @test
     */
    public function hasIncludedRelationshipsWhenTrue()
    {
        $request = $this->createRequestWithQueryParams(["include" => "authors"]);

        $hasIncludedRelationships = $request->hasIncludedRelationships();

        $this->assertTrue($hasIncludedRelationships);
    }

    /**
     * @test
     */
    public function hasIncludedRelationshipsWhenFalse()
    {
        $queryParams = ["include" => ""];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertFalse($request->hasIncludedRelationships());

        $queryParams = [];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertFalse($request->hasIncludedRelationships());
    }

    /**
     * @test
     */
    public function getIncludedEmptyRelationshipsWhenEmpty()
    {
        $baseRelationshipPath = "book";
        $includedRelationships = [];
        $queryParams = ["include" => ""];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertEquals($includedRelationships, $request->getIncludedRelationships($baseRelationshipPath));

        $baseRelationshipPath = "book";
        $includedRelationships = [];
        $queryParams = [];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertEquals($includedRelationships, $request->getIncludedRelationships($baseRelationshipPath));
    }

    /**
     * @test
     */
    public function getIncludedRelationshipsForPrimaryResource()
    {
        $baseRelationshipPath = "";
        $includedRelationships = ["authors"];
        $queryParams = ["include" => implode(",", $includedRelationships)];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertEquals($includedRelationships, $request->getIncludedRelationships($baseRelationshipPath));
    }

    /**
     * @test
     */
    public function getIncludedRelationshipsForEmbeddedResource()
    {
        $baseRelationshipPath = "book";
        $includedRelationships = ["authors"];
        $queryParams = ["include" => "book,book.authors"];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertEquals($includedRelationships, $request->getIncludedRelationships($baseRelationshipPath));
    }

    /**
     * @test
     */
    public function getIncludedRelationshipsForMultipleEmbeddedResource()
    {
        $baseRelationshipPath = "book.authors";
        $includedRelationships = ["contacts", "address"];
        $queryParams = ["include" => "book,book.authors,book.authors.contacts,book.authors.address"];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertEquals($includedRelationships, $request->getIncludedRelationships($baseRelationshipPath));
    }

    /**
     * @test
     */
    public function getIncludedRelationshipsWhenMalformed()
    {
        $this->expectException(QueryParamMalformed::class);

        $queryParams = ["include" => []];

        $request = $this->createRequestWithQueryParams($queryParams);
        $request->getIncludedRelationships("");
    }

    /**
     * @test
     */
    public function isIncludedRelationshipForPrimaryResourceWhenEmpty()
    {
        $baseRelationshipPath = "";
        $requiredRelationship = "authors";
        $defaultRelationships = [];
        $queryParams = ["include" => ""];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertFalse(
            $request->isIncludedRelationship($baseRelationshipPath, $requiredRelationship, $defaultRelationships)
        );
    }

    /**
     * @test
     */
    public function isIncludedRelationshipForPrimaryResourceWhenEmptyWithDefault()
    {
        $baseRelationshipPath = "";
        $requiredRelationship = "authors";
        $defaultRelationships = ["publisher" => true];
        $queryParams = [];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertFalse(
            $request->isIncludedRelationship($baseRelationshipPath, $requiredRelationship, $defaultRelationships)
        );
    }

    /**
     * @test
     */
    public function isIncludedRelationshipForPrimaryResourceWithDefault()
    {
        $baseRelationshipPath = "";
        $requiredRelationship = "authors";
        $defaultRelationships = ["publisher" => true];
        $queryParams = ["include" => "editors"];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertFalse(
            $request->isIncludedRelationship($baseRelationshipPath, $requiredRelationship, $defaultRelationships)
        );
    }

    /**
     * @test
     */
    public function isIncludedRelationshipForEmbeddedResource()
    {
        $baseRelationshipPath = "authors";
        $requiredRelationship = "contacts";
        $defaultRelationships = [];
        $queryParams = ["include" => "authors,authors.contacts"];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertTrue(
            $request->isIncludedRelationship($baseRelationshipPath, $requiredRelationship, $defaultRelationships)
        );
    }

    /**
     * @test
     */
    public function isIncludedRelationshipForEmbeddedResourceWhenDefaulted()
    {
        $baseRelationshipPath = "authors";
        $requiredRelationship = "contacts";
        $defaultRelationships = ["contacts" => true];
        $queryParams = ["include" => ""];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertFalse(
            $request->isIncludedRelationship($baseRelationshipPath, $requiredRelationship, $defaultRelationships)
        );
    }

    /**
     * @test
     */
    public function getSortingWhenEmpty()
    {
        $sorting = [];
        $queryParams = ["sort" => ""];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertEquals($sorting, $request->getSorting());
    }

    /**
     * @test
     */
    public function getSortingWhenNotEmpty()
    {
        $request = $this->createRequestWithQueryParams(
            [
                "sort" => "name,age,sex",
            ]
        );

        $sorting = $request->getSorting();

        $this->assertEquals(["name", "age", "sex"], $sorting);
    }

    /**
     * @test
     */
    public function getSortingWhenMalformed()
    {
        $request = $this->createRequestWithQueryParams(
            [
                "sort" => ["name" => "asc"],
            ]
        );

        $this->expectException(QueryParamMalformed::class);

        $request->getSorting();
    }

    /**
     * @test
     */
    public function getPaginationWhenEmpty()
    {
        $pagination = [];
        $queryParams = ["page" => []];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertEquals($pagination, $request->getPagination());
    }

    /**
     * @test
     */
    public function getPaginationWhenNotEmpty()
    {
        $request = $this->createRequestWithQueryParams(
            [
                "page" => ["number" => "1", "size" => "10"],
            ]
        );

        $pagination = $request->getPagination();

        $this->assertEquals(["number" => "1", "size" => "10"], $pagination);
    }

    /**
     * @test
     */
    public function getPaginationWhenMalformed()
    {
        $request = $this->createRequestWithQueryParams(
            [
                "page" => "",
            ]
        );

        $this->expectException(QueryParamMalformed::class);

        $request->getPagination();
    }

    /**
     * @test
     */
    public function getFilteringWhenEmpty()
    {
        $request = $this->createRequestWithQueryParams(
            [
                "filter" => [],
            ]
        );

        $filtering = $request->getFiltering();

        $this->assertEmpty($filtering);
    }

    /**
     * @test
     */
    public function getFilteringWhenNotEmpty()
    {
        $request = $this->createRequestWithQueryParams(
            [
                "filter" => ["name" => "John"],
            ]
        );

        $filtering = $request->getFiltering();

        $this->assertEquals(["name" => "John"], $filtering);
    }

    /**
     * @test
     */
    public function getFilteringWhenMalformed()
    {
        $request = $this->createRequestWithQueryParams(
            [
                "filter" => "",
            ]
        );

        $this->expectException(QueryParamMalformed::class);

        $request->getFiltering();
    }

    /**
     * @test
     */
    public function getFilteringParam()
    {
        $request = $this->createRequestWithQueryParams(
            [
                "filter" => ["name" => "John"],
            ]
        );

        $filteringParam = $request->getFilteringParam("name");

        $this->assertEquals("John", $filteringParam);
    }

    /**
     * @test
     */
    public function getDefaultFilteringParamWhenNotFound()
    {
        $request = $this->createRequestWithQueryParams(
            [
                "filter" => ["name" => "John"],
            ]
        );

        $filteringParam = $request->getFilteringParam("age", false);

        $this->assertFalse($filteringParam);
    }

    /**
     * @test
     */
    public function getAppliedProfilesWhenEmpty()
    {
        $request = $this->createRequestWithHeader("content-type", "application/vnd.api+json");

        $profiles = $request->getAppliedProfiles();

        $this->assertEmpty($profiles);
    }

    /**
     * @test
     */
    public function getAppliedProfilesWhenOneProfile()
    {
        $request = $this->createRequestWithHeader(
            "content-type",
            "application/vnd.api+json;profile=https://example.com/extensions/last-modified"
        );

        $profiles = $request->getAppliedProfiles();

        $this->assertEquals(
            [
                "https://example.com/extensions/last-modified",
            ],
            $profiles
        );
    }

    /**
     * @test
     */
    public function getAppliedProfilesWhenTwoProfiles()
    {
        $request = $this->createRequestWithHeader(
            "content-type",
            'application/vnd.api+json;profile="https://example.com/extensions/last-modified https://example.com/extensions/created"'
        );

        $profiles = $request->getAppliedProfiles();

        $this->assertEquals(
            [
                "https://example.com/extensions/last-modified",
                "https://example.com/extensions/created",
            ],
            $profiles
        );
    }

    /**
     * @test
     */
    public function getAppliedProfilesWhenMultipleJsonApiContentTypes()
    {
        $request = $this->createRequestWithHeader(
            "content-type",
            'application/vnd.api+json;profile = https://example.com/extensions/last-modified, ' .
            'application/vnd.api+json;profile="https://example.com/extensions/last-modified https://example.com/extensions/created"'
        );

        $profiles = $request->getAppliedProfiles();

        $this->assertEquals(
            [
                "https://example.com/extensions/last-modified",
                "https://example.com/extensions/created",
            ],
            $profiles
        );
    }

    /**
     * @test
     */
    public function isProfileAppliedWhenTrue()
    {
        $request = $this->createRequestWithHeader(
            "content-type",
            'application/vnd.api+json;profile="https://example.com/extensions/last-modified https://example.com/extensions/created"'
        );

        $isProfileApplied = $request->isProfileApplied("https://example.com/extensions/created");

        $this->assertTrue($isProfileApplied);
    }

    /**
     * @test
     */
    public function isProfileAppliedWhenFalse()
    {
        $request = $this->createRequestWithHeader(
            "content-type",
            'application/vnd.api+json;profile="https://example.com/extensions/last-modified https://example.com/extensions/created"'
        );

        $isProfileApplied = $request->isProfileApplied("https://example.com/extensions/inexistent-profile");

        $this->assertFalse($isProfileApplied);
    }

    /**
     * @test
     */
    public function getRequestedProfilesWhenEmpty()
    {
        $request = $this->createRequestWithHeader("accept", "application/vnd.api+json");

        $profiles = $request->getRequestedProfiles();

        $this->assertEmpty($profiles);
    }

    /**
     * @test
     */
    public function getRequestedProfilesWhenTwoProfiles()
    {
        $request = $this->createRequestWithHeader(
            "accept",
            'application/vnd.api+json;profile="https://example.com/extensions/last-modified https://example.com/extensions/created"'
        );

        $profiles = $request->getRequestedProfiles();

        $this->assertEquals(
            [
                "https://example.com/extensions/last-modified",
                "https://example.com/extensions/created",
            ],
            $profiles
        );
    }

    /**
     * @test
     */
    public function isProfileRequestedWhenTrue()
    {
        $request = $this->createRequestWithHeader(
            "accept",
            'application/vnd.api+json;profile="https://example.com/extensions/last-modified https://example.com/extensions/created"'
        );

        $isProfileRequested = $request->isProfileRequested("https://example.com/extensions/created");

        $this->assertTrue($isProfileRequested);
    }

    /**
     * @test
     */
    public function isProfileRequestedWhenFalse()
    {
        $request = $this->createRequestWithHeader(
            "accept",
            'application/vnd.api+json;profile="https://example.com/extensions/last-modified https://example.com/extensions/created"'
        );

        $isProfileRequested = $request->isProfileRequested("https://example.com/extensions/inexistent-profile");

        $this->assertFalse($isProfileRequested);
    }

    /**
     * @test
     */
    public function getRequiredProfilesWhenMalformed()
    {
        $request = $this->createRequestWithQueryParams(
            [
                "profile" => [],
            ]
        );

        $this->expectException(QueryParamMalformed::class);

        $request->getRequiredProfiles();
    }

    /**
     * @test
     */
    public function getRequiredProfilesWhenEmpty()
    {
        $request = $this->createRequestWithQueryParams(
            [
                "profile" => "",
            ]
        );

        $profiles = $request->getRequiredProfiles();

        $this->assertEmpty($profiles);
    }

    /**
     * @test
     */
    public function getRequiredProfilesWhenTwoProfiles()
    {
        $request = $this->createRequestWithQueryParams(
            [
                "profile" => "https://example.com/extensions/last-modified https://example.com/extensions/created",
            ]
        );

        $profiles = $request->getRequiredProfiles();

        $this->assertEquals(
            [
                "https://example.com/extensions/last-modified",
                "https://example.com/extensions/created",
            ],
            $profiles
        );
    }

    /**
     * @test
     */
    public function isProfileRequiredWhenTrue()
    {
        $request = $this->createRequestWithQueryParams(
            [
                "profile" => "https://example.com/extensions/last-modified https://example.com/extensions/created",
            ]
        );

        $isProfileRequired = $request->isProfileRequired("https://example.com/extensions/created");

        $this->assertTrue($isProfileRequired);
    }

    /**
     * @test
     */
    public function isProfileRequiredWhenFalse()
    {
        $request = $this->createRequestWithQueryParams(
            [
                "profile" => "https://example.com/extensions/last-modified https://example.com/extensions/created",
            ]
        );

        $isProfileRequired = $request->isProfileRequired("https://example.com/extensions/inexistent-profile");

        $this->assertFalse($isProfileRequired);
    }

    /**
     * @test
     */
    public function withHeaderInvalidatesParsedJsonApiHeaders()
    {
        $request = $this->createRequest()
            ->withHeader(
                "content-type",
                "application/vnd.api+json;profile=https://example.com/extensions/last-modified"
            )
            ->withHeader(
                "accept",
                "application/vnd.api+json;profile=https://example.com/extensions/last-modified"
            )
        ;

        $request->getAppliedProfiles();
        $request->getRequestedProfiles();

        $request = $request
            ->withHeader(
                "content-type",
                "application/vnd.api+json;profile=https://example.com/extensions/created"
            )
            ->withHeader(
                "accept",
                "application/vnd.api+json;profile=https://example.com/extensions/created"
            );

        $this->assertEquals(["https://example.com/extensions/created"], $request->getAppliedProfiles());
        $this->assertEquals(["https://example.com/extensions/created"], $request->getRequestedProfiles());
    }

    /**
     * @test
     */
    public function getResourceWhenEmpty()
    {
        $request = $this->createRequestWithJsonBody([]);

        $resource = $request->getResource();

        $this->assertNull($resource);
    }

    /**
     * @test
     */
    public function getResource()
    {
        $request = $this->createRequestWithJsonBody(
            [
                "data" => [],
            ]
        );

        $resource = $request->getResource();

        $this->assertEquals([], $resource);
    }

    /**
     * @test
     */
    public function getResourceTypeWhenEmpty()
    {
        $request = $this->createRequestWithJsonBody([]);

        $type = $request->getResourceType();

        $this->assertNull($type);
    }

    /**
     * @test
     */
    public function getResourceType()
    {
        $request = $this->createRequestWithJsonBody(
            [
                "data" => [
                    "type" => "user",
                ],
            ]
        );

        $type = $request->getResourceType();

        $this->assertEquals("user", $type);
    }

    /**
     * @test
     */
    public function getResourceIdWhenEmpty()
    {
        $request = $this->createRequestWithJsonBody(
            [
                "data" => [],
            ]
        );

        $id = $request->getResourceId();

        $this->assertNull($id);
    }

    /**
     * @test
     */
    public function getResourceId()
    {
        $request = $this->createRequestWithJsonBody(
            [
                "data" => [
                    "id" => "1",
                ],
            ]
        );

        $id = $request->getResourceId();

        $this->assertEquals("1", $id);
    }

    /**
     * @test
     */
    public function getResourceAttributes()
    {
        $request = $this->createRequestWithJsonBody(
            [
                "data" => [
                    "type" => "dog",
                    "id" => "1",
                    "attributes" => [
                        "name" => "Hot dog",
                    ],
                ],
            ]
        );

        $attributes = $request->getResourceAttributes();

        $this->assertEquals(
            [
                "name" => "Hot dog",
            ],
            $attributes
        );
    }

    /**
     * @test
     */
    public function getResourceAttribute()
    {
        $request = $this->createRequestWithJsonBody(
            [
                "data" => [
                    "type" => "dog",
                    "id" => "1",
                    "attributes" => [
                        "name" => "Hot dog",
                    ],
                ],
            ]
        );

        $name = $request->getResourceAttribute("name");

        $this->assertEquals("Hot dog", $name);
    }

    /**
     * @test
     */
    public function hasToOneRelationshipWhenTrue()
    {
        $request = $this->createRequestWithJsonBody(
            [
                "data" => [
                    "type" => "dog",
                    "id" => "1",
                    "relationships" => [
                        "owner" => [
                            "data" => ["type" => "human", "id" => "1"],
                        ],
                    ],
                ],
            ]
        );

        $hasToOneRelationship = $request->hasToOneRelationship("owner");

        $this->assertTrue($hasToOneRelationship);
    }

    /**
     * @test
     */
    public function hasToOneRelationshipWhenFalse()
    {
        $request = $this->createRequestWithJsonBody(
            [
                "data" => [
                    "type" => "dog",
                    "id" => "1",
                    "relationships" => [],
                ],
            ]
        );

        $hasToOneRelationship = $request->hasToOneRelationship("owner");

        $this->assertFalse($hasToOneRelationship);
    }

    /**
     * @test
     */
    public function getToOneRelationship()
    {
        $request = $this->createRequestWithJsonBody(
            [
                "data" => [
                    "type" => "dog",
                    "id" => "1",
                    "relationships" => [
                        "owner" => [
                            "data" => ["type" => "human", "id" => "1"],
                        ],
                    ],
                ],
            ]
        );

        $resourceIdentifier = $request->getToOneRelationship("owner")->getResourceIdentifier();
        $type = $resourceIdentifier->getType();
        $id = $resourceIdentifier->getId();

        $this->assertEquals("human", $type);
        $this->assertEquals("1", $id);
    }

    /**
     * @test
     */
    public function getDeletingToOneRelationship()
    {
        $request = $this->createRequestWithJsonBody(
            [
                "data" => [
                    "type" => "dog",
                    "id" => "1",
                    "relationships" => [
                        "owner" => [
                            "data" => null,
                        ],
                    ],
                ],
            ]
        );

        $isEmpty = $request->getToOneRelationship("owner")->isEmpty();

        $this->assertTrue($isEmpty);
    }

    /**
     * @test
     */
    public function getToOneRelationshiWhenNotExists()
    {
        $request = $this->createRequestWithJsonBody(
            [
                "data" => [
                    "type" => "dog",
                    "id" => "1",
                    "relationships" => [
                    ],
                ],
            ]
        );

        $this->expectException(RelationshipNotExists::class);

        $request->getToOneRelationship("owner");
    }

    /**
     * @test
     */
    public function hasToManyRelationshipWhenTrue()
    {
        $request = $this->createRequestWithJsonBody(
            [
                "data" => [
                    "type" => "dog",
                    "id" => "1",
                    "relationships" => [
                        "friends" => [
                            "data" => [
                                ["type" => "dog", "id" => "2"],
                                ["type" => "dog", "id" => "3"],
                            ],
                        ],
                    ],
                ],
            ]
        );

        $hasRelationship = $request->hasToManyRelationship("friends");

        $this->assertTrue($hasRelationship);
    }

    /**
     * @test
     */
    public function hasToManyRelationshipWhenFalse()
    {
        $request = $this->createRequestWithJsonBody(
            [
                "data" => [
                    "type" => "dog",
                    "id" => "1",
                    "relationships" => [
                    ],
                ],
            ]
        );

        $hasRelationship = $request->hasToManyRelationship("friends");

        $this->assertFalse($hasRelationship);
    }

    /**
     * @test
     */
    public function getToManyRelationship()
    {
        $request = $this->createRequestWithJsonBody(
            [
                "data" => [
                    "type" => "dog",
                    "id" => "1",
                    "relationships" => [
                        "friends" => [
                            "data" => [
                                ["type" => "dog", "id" => "2"],
                                ["type" => "dog", "id" => "3"],
                            ],
                        ],
                    ],
                ],
            ]
        );

        $resourceIdentifiers = $request->getToManyRelationship("friends")->getResourceIdentifiers();

        $this->assertEquals("dog", $resourceIdentifiers[0]->getType());
        $this->assertEquals("2", $resourceIdentifiers[0]->getId());
        $this->assertEquals("dog", $resourceIdentifiers[1]->getType());
        $this->assertEquals("3", $resourceIdentifiers[1]->getId());
    }

    /**
     * @test
     */
    public function getToManyRelationshipWhenNotExists()
    {
        $request = $this->createRequestWithJsonBody(
            [
                "data" => [
                    "type" => "dog",
                    "id" => "1",
                    "relationships" => [
                    ],
                ],
            ]
        );

        $this->expectException(RelationshipNotExists::class);

        $request->getToManyRelationship("friends");
    }

    /**
     * @test
     */
    public function withQueryParamsInvalidatesParsedJsonApiQueryParams()
    {
        $request = $this->createRequestWithQueryParams(
            [
                "fields" => ["book" => "title,pages"],
                "include" => "authors",
                "page" => ["offset" => 0, "limit" => 10],
                "filter" => ["title" => "Working Effectively with Unit Tests"],
                "sort" => "title",
                "profile" => "https://example.com/extensions/last-modified",
            ]
        );

        $request->getIncludedFields("");
        $request->getIncludedRelationships("");
        $request->getPagination();
        $request->getFiltering();
        $request->getSorting();
        $request->getRequiredProfiles();

        $request = $request->withQueryParams(
            [
                "fields" => ["book" => "isbn"],
                "include" => "publisher",
                "page" => ["number" => 1, "size" => 10],
                "filter" => ["title" => "Building Microservices"],
                "sort" => "isbn",
                "profile" => "https://example.com/extensions/created",
            ]
        );

        $this->assertEquals(["isbn"], $request->getIncludedFields("book"));
        $this->assertEquals(["publisher"], $request->getIncludedRelationships(""));
        $this->assertEquals(["number" => 1, "size" => 10], $request->getPagination());
        $this->assertEquals(["title" => "Building Microservices"], $request->getFiltering());
        $this->assertEquals(["isbn"], $request->getSorting());
        $this->assertEquals(["https://example.com/extensions/created"], $request->getRequiredProfiles());
    }

    private function createRequest(): JsonApiRequest
    {
        return new JsonApiRequest(new ServerRequest(), new DefaultExceptionFactory(), new JsonDeserializer());
    }

    private function createRequestWithJsonBody(array $body): JsonApiRequest
    {
        $psrRequest = new ServerRequest();
        $psrRequest = $psrRequest->withParsedBody($body);

        return new JsonApiRequest($psrRequest, new DefaultExceptionFactory(), new JsonDeserializer());
    }

    private function createRequestWithHeader($headerName, $headerValue): JsonApiRequest
    {
        $psrRequest = new ServerRequest([], [], null, null, "php://temp", [$headerName => $headerValue]);

        return new JsonApiRequest($psrRequest, new DefaultExceptionFactory(), new JsonDeserializer());
    }

    private function createRequestWithQueryParams(array $queryParams): JsonApiRequest
    {
        $psrRequest = new ServerRequest();
        $psrRequest = $psrRequest->withQueryParams($queryParams);

        return new JsonApiRequest($psrRequest, new DefaultExceptionFactory(), new JsonDeserializer());
    }
}
