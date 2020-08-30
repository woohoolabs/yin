<?php

declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Request;

use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;
use WoohooLabs\Yin\JsonApi\Exception\MediaTypeUnacceptable;
use WoohooLabs\Yin\JsonApi\Exception\MediaTypeUnsupported;
use WoohooLabs\Yin\JsonApi\Exception\QueryParamMalformed;
use WoohooLabs\Yin\JsonApi\Exception\QueryParamUnrecognized;
use WoohooLabs\Yin\JsonApi\Exception\RelationshipNotExists;
use WoohooLabs\Yin\JsonApi\Exception\RequiredTopLevelMembersMissing;
use WoohooLabs\Yin\JsonApi\Exception\TopLevelMemberNotAllowed;
use WoohooLabs\Yin\JsonApi\Exception\TopLevelMembersIncompatible;
use WoohooLabs\Yin\JsonApi\Request\JsonApiRequest;
use WoohooLabs\Yin\JsonApi\Serializer\JsonDeserializer;

use function implode;

class JsonApiRequestTest extends TestCase
{
    /**
     * @test
     */
    public function validateJsonApiContentTypeHeader(): void
    {
        $request = $this->createRequestWithHeader("content-type", "application/vnd.api+json");

        $request->validateContentTypeHeader();

        $this->addToAssertionCount(1);
    }

    /**
     * @test
     */
    public function validateJsonApiContentTypeHeaderWithSemicolon(): void
    {
        $request = $this->createRequestWithHeader("content-type", "application/vnd.api+json;");

        $request->validateContentTypeHeader();

        $this->addToAssertionCount(1);
    }

    /**
     * @test
     */
    public function validateEmptyContentTypeHeader(): void
    {
        $request = $this->createRequestWithHeader("content-type", "");

        $request->validateContentTypeHeader();

        $this->addToAssertionCount(1);
    }

    /**
     * @test
     */
    public function validateHtmlContentTypeHeader(): void
    {
        $request = $this->createRequestWithHeader("content-type", "text/html; charset=utf-8");

        $request->validateContentTypeHeader();

        $this->addToAssertionCount(1);
    }

    /**
     * @test
     */
    public function validateMultipleMediaTypeContentTypeHeader(): void
    {
        $request = $this->createRequestWithHeader("content-type", "application/vnd.api+json, text/*;q=0.3, text/html;q=0.7");

        $request->validateContentTypeHeader();

        $this->addToAssertionCount(1);
    }

    /**
     * @test
     */
    public function validateCaseInsensitiveContentTypeHeader(): void
    {
        $request = $this->createRequestWithHeader("content-type", "Application/vnd.Api+JSON, text/*;q=0.3, text/html;Q=0.7");

        $request->validateContentTypeHeader();

        $this->addToAssertionCount(1);
    }

    /**
     * @test
     */
    public function validateInvalidContentTypeHeaderWithExtMediaType(): void
    {
        $request = $this->createRequestWithHeader("content-type", 'application/vnd.api+json; ext="ext1,ext2"');

        $this->expectException(MediaTypeUnsupported::class);

        $request->validateContentTypeHeader();
    }

    /**
     * @test
     */
    public function validateInvalidContentTypeHeaderWithWhitespaceBeforeParameter(): void
    {
        $request = $this->createRequestWithHeader("content-type", 'application/vnd.api+json ; ext="ext1,ext2"');

        $this->expectException(MediaTypeUnsupported::class);

        $request->validateContentTypeHeader();
    }

    /**
     * @test
     */
    public function validateContentTypeHeaderWithJsonApiProfileMediaTypeParameter(): void
    {
        $request = $this->createRequestWithHeader(
            "content-type",
            "application/vnd.api+json;profile=https://example.com/profiles/last-modified"
        );

        $request->validateContentTypeHeader();

        $this->addToAssertionCount(1);
    }

    /**
     * @test
     */
    public function validateContentTypeHeaderWithInvalidMediaTypeParameter(): void
    {
        $request = $this->createRequestWithHeader("content-type", "application/vnd.api+json; Charset=utf-8");

        $this->expectException(MediaTypeUnsupported::class);

        $request->validateContentTypeHeader();
    }

    /**
     * @test
     */
    public function validateAcceptHeaderWithJsonApiMediaType(): void
    {
        $request = $this->createRequestWithHeader("accept", "application/vnd.api+json");

        $request->validateAcceptHeader();

        $this->addToAssertionCount(1);
    }

    /**
     * @test
     */
    public function validateAcceptHeaderWithJsonApiProfileMediaTypeParameter(): void
    {
        $request = $this->createRequestWithHeader(
            "content-type",
            "application/vnd.api+json; Profile = https://example.com/profiles/last-modified"
        );

        $request->validateContentTypeHeader();

        $this->addToAssertionCount(1);
    }

    /**
     * @test
     */
    public function validateAcceptHeaderWithInvalidMediaTypeParameters(): void
    {
        $request = $this->createRequestWithHeader("accept", 'application/vnd.api+json; ext="ext1,ext2"; charset=utf-8; lang=en');

        $this->expectException(MediaTypeUnacceptable::class);

        $request->validateAcceptHeader();
    }

    /**
     * @test
     */
    public function validateEmptyQueryParams(): void
    {
        $request = $this->createRequestWithQueryParams([]);

        $request->validateQueryParams();

        $this->addToAssertionCount(1);
    }

    /**
     * @test
     */
    public function validateBasicQueryParams(): void
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
    public function validateInvalidQueryParams(): void
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
    public function validateTopLevelMembersWhenEmpty(): void
    {
        $request = $this->createRequestWithJsonBody(
            []
        );

        $this->expectException(RequiredTopLevelMembersMissing::class);

        $request->validateTopLevelMembers();
    }

    /**
     * @test
     */
    public function validateTopLevelMembersWhenDataAndErrors(): void
    {
        $request = $this->createRequestWithJsonBody(
            [
                "data" => [],
                "errors" => [],
            ]
        );

        $this->expectException(TopLevelMembersIncompatible::class);

        $request->validateTopLevelMembers();
    }

    /**
     * @test
     */
    public function validateTopLevelMembersWhenIncludedWithoutData(): void
    {
        $request = $this->createRequestWithJsonBody(
            [
                "errors" => [],
                "included" => [],
            ]
        );

        $this->expectException(TopLevelMemberNotAllowed::class);

        $request->validateTopLevelMembers();
    }

    /**
     * @test
     */
    public function validateTopLevelMembersWhenData(): void
    {
        $request = $this->createRequestWithJsonBody(
            [
                "data" => [],
            ]
        );

        $request->validateTopLevelMembers();

        $this->addToAssertionCount(1);
    }

    /**
     * @test
     */
    public function validateTopLevelMembersWhenDataAndIncluded(): void
    {
        $request = $this->createRequestWithJsonBody(
            [
                "data" => [],
                "included" => [],
            ]
        );

        $request->validateTopLevelMembers();

        $this->addToAssertionCount(1);
    }

    /**
     * @test
     */
    public function validateTopLevelMembersWhenErrors(): void
    {
        $request = $this->createRequestWithJsonBody(
            [
                "errors" => [],
            ]
        );

        $request->validateTopLevelMembers();

        $this->addToAssertionCount(1);
    }

    /**
     * @test
     */
    public function getIncludedFieldsWhenEmpty(): void
    {
        $request = $this->createRequestWithQueryParams([]);

        $includedFields = $request->getIncludedFields("");

        $this->assertEquals([], $includedFields);
    }

    /**
     * @test
     */
    public function getIncludedFieldsForResource(): void
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
    public function getIncludedFieldsForUnspecifiedResource(): void
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
    public function getIncludedFieldWhenMalformed(): void
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
    public function getIncludedFieldWhenFieldMalformed(): void
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
    public function isIncludedFieldWhenAllFieldsRequested(): void
    {
        $request = $this->createRequestWithQueryParams(["fields" => []]);
        $this->assertTrue($request->isIncludedField("book", "title"));

        $request = $this->createRequestWithQueryParams([]);
        $this->assertTrue($request->isIncludedField("book", "title"));
    }

    /**
     * @test
     */
    public function isIncludedFieldWhenNoFieldRequested(): void
    {
        $request = $this->createRequestWithQueryParams(["fields" => ["book1" => ""]]);

        $isIncludedField = $request->isIncludedField("book1", "title");

        $this->assertFalse($isIncludedField);
    }

    /**
     * @test
     */
    public function isIncludedFieldWhenGivenFieldIsSpecified(): void
    {
        $request = $this->createRequestWithQueryParams(["fields" => ["book" => "title,pages"]]);

        $isIncludedField = $request->isIncludedField("book", "title");

        $this->assertTrue($isIncludedField);
    }

    /**
     * @test
     */
    public function hasIncludedRelationshipsWhenTrue(): void
    {
        $request = $this->createRequestWithQueryParams(["include" => "authors"]);

        $hasIncludedRelationships = $request->hasIncludedRelationships();

        $this->assertTrue($hasIncludedRelationships);
    }

    /**
     * @test
     */
    public function hasIncludedRelationshipsWhenFalse(): void
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
    public function getIncludedEmptyRelationshipsWhenEmpty(): void
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
    public function getIncludedRelationshipsForPrimaryResource(): void
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
    public function getIncludedRelationshipsForEmbeddedResource(): void
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
    public function getIncludedRelationshipsForMultipleEmbeddedResource(): void
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
    public function getIncludedRelationshipsWhenMalformed(): void
    {
        $this->expectException(QueryParamMalformed::class);

        $queryParams = ["include" => []];

        $request = $this->createRequestWithQueryParams($queryParams);
        $request->getIncludedRelationships("");
    }

    /**
     * @test
     */
    public function isIncludedRelationshipForPrimaryResourceWhenEmpty(): void
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
    public function isIncludedRelationshipForPrimaryResourceWhenEmptyWithDefault(): void
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
    public function isIncludedRelationshipForPrimaryResourceWithDefault(): void
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
    public function isIncludedRelationshipForEmbeddedResource(): void
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
    public function isIncludedRelationshipForEmbeddedResourceWhenDefaulted(): void
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
    public function getSortingWhenEmpty(): void
    {
        $sorting = [];
        $queryParams = ["sort" => ""];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertEquals($sorting, $request->getSorting());
    }

    /**
     * @test
     */
    public function getSortingWhenNotEmpty(): void
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
    public function getSortingWhenMalformed(): void
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
    public function getPaginationWhenEmpty(): void
    {
        $pagination = [];
        $queryParams = ["page" => []];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertEquals($pagination, $request->getPagination());
    }

    /**
     * @test
     */
    public function getPaginationWhenNotEmpty(): void
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
    public function getPaginationWhenMalformed(): void
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
    public function getFilteringWhenEmpty(): void
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
    public function getFilteringWhenNotEmpty(): void
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
    public function getFilteringWhenMalformed(): void
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
    public function getFilteringParam(): void
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
    public function getDefaultFilteringParamWhenNotFound(): void
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
    public function getAppliedProfilesWhenEmpty(): void
    {
        $request = $this->createRequestWithHeader("content-type", "application/vnd.api+json");

        $profiles = $request->getAppliedProfiles();

        $this->assertEmpty($profiles);
    }

    /**
     * @test
     */
    public function getAppliedProfilesWhenOneProfile(): void
    {
        $request = $this->createRequestWithHeader(
            "content-type",
            "application/vnd.api+json;profile=https://example.com/profiles/last-modified"
        );

        $profiles = $request->getAppliedProfiles();

        $this->assertEquals(
            [
                "https://example.com/profiles/last-modified",
            ],
            $profiles
        );
    }

    /**
     * @test
     */
    public function getAppliedProfilesWhenTwoProfiles(): void
    {
        $request = $this->createRequestWithHeader(
            "content-type",
            'application/vnd.api+json;profile="https://example.com/profiles/last-modified https://example.com/profiles/created"'
        );

        $profiles = $request->getAppliedProfiles();

        $this->assertEquals(
            [
                "https://example.com/profiles/last-modified",
                "https://example.com/profiles/created",
            ],
            $profiles
        );
    }

    /**
     * @test
     */
    public function getAppliedProfilesWhenMultipleJsonApiContentTypes(): void
    {
        $request = $this->createRequestWithHeader(
            "content-type",
            'application/vnd.api+json;profile = https://example.com/profiles/last-modified, ' .
            'application/vnd.api+json;profile="https://example.com/profiles/last-modified https://example.com/profiles/created"'
        );

        $profiles = $request->getAppliedProfiles();

        $this->assertEquals(
            [
                "https://example.com/profiles/last-modified",
                "https://example.com/profiles/created",
            ],
            $profiles
        );
    }

    /**
     * @test
     */
    public function isProfileAppliedWhenTrue(): void
    {
        $request = $this->createRequestWithHeader(
            "content-type",
            'application/vnd.api+json;profile="https://example.com/profiles/last-modified https://example.com/profiles/created"'
        );

        $isProfileApplied = $request->isProfileApplied("https://example.com/profiles/created");

        $this->assertTrue($isProfileApplied);
    }

    /**
     * @test
     */
    public function isProfileAppliedWhenFalse(): void
    {
        $request = $this->createRequestWithHeader(
            "content-type",
            'application/vnd.api+json;profile="https://example.com/profiles/last-modified https://example.com/profiles/created"'
        );

        $isProfileApplied = $request->isProfileApplied("https://example.com/profiles/inexistent-profile");

        $this->assertFalse($isProfileApplied);
    }

    /**
     * @test
     */
    public function getRequestedProfilesWhenEmpty(): void
    {
        $request = $this->createRequestWithHeader("accept", "application/vnd.api+json");

        $profiles = $request->getRequestedProfiles();

        $this->assertEmpty($profiles);
    }

    /**
     * @test
     */
    public function getRequestedProfilesWhenTwoProfiles(): void
    {
        $request = $this->createRequestWithHeader(
            "accept",
            'application/vnd.api+json;profile="https://example.com/profiles/last-modified https://example.com/profiles/created"'
        );

        $profiles = $request->getRequestedProfiles();

        $this->assertEquals(
            [
                "https://example.com/profiles/last-modified",
                "https://example.com/profiles/created",
            ],
            $profiles
        );
    }

    /**
     * @test
     */
    public function isProfileRequestedWhenTrue(): void
    {
        $request = $this->createRequestWithHeader(
            "accept",
            'application/vnd.api+json;profile="https://example.com/profiles/last-modified https://example.com/profiles/created"'
        );

        $isProfileRequested = $request->isProfileRequested("https://example.com/profiles/created");

        $this->assertTrue($isProfileRequested);
    }

    /**
     * @test
     */
    public function isProfileRequestedWhenFalse(): void
    {
        $request = $this->createRequestWithHeader(
            "accept",
            'application/vnd.api+json;profile="https://example.com/profiles/last-modified https://example.com/profiles/created"'
        );

        $isProfileRequested = $request->isProfileRequested("https://example.com/profiles/inexistent-profile");

        $this->assertFalse($isProfileRequested);
    }

    /**
     * @test
     */
    public function getRequiredProfilesWhenMalformed(): void
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
    public function getRequiredProfilesWhenEmpty(): void
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
    public function getRequiredProfilesWhenTwoProfiles(): void
    {
        $request = $this->createRequestWithQueryParams(
            [
                "profile" => "https://example.com/profiles/last-modified https://example.com/profiles/created",
            ]
        );

        $profiles = $request->getRequiredProfiles();

        $this->assertEquals(
            [
                "https://example.com/profiles/last-modified",
                "https://example.com/profiles/created",
            ],
            $profiles
        );
    }

    /**
     * @test
     */
    public function isProfileRequiredWhenTrue(): void
    {
        $request = $this->createRequestWithQueryParams(
            [
                "profile" => "https://example.com/profiles/last-modified https://example.com/profiles/created",
            ]
        );

        $isProfileRequired = $request->isProfileRequired("https://example.com/profiles/created");

        $this->assertTrue($isProfileRequired);
    }

    /**
     * @test
     */
    public function isProfileRequiredWhenFalse(): void
    {
        $request = $this->createRequestWithQueryParams(
            [
                "profile" => "https://example.com/profiles/last-modified https://example.com/profiles/created",
            ]
        );

        $isProfileRequired = $request->isProfileRequired("https://example.com/profiles/inexistent-profile");

        $this->assertFalse($isProfileRequired);
    }

    /**
     * @test
     */
    public function withHeaderInvalidatesParsedJsonApiHeaders(): void
    {
        $request = $this->createRequest()
            ->withHeader(
                "content-type",
                "application/vnd.api+json;profile=https://example.com/profiles/last-modified"
            )
            ->withHeader(
                "accept",
                "application/vnd.api+json;profile=https://example.com/profiles/last-modified"
            );

        $request->getAppliedProfiles();
        $request->getRequestedProfiles();

        $request = $request
            ->withHeader(
                "content-type",
                "application/vnd.api+json;profile=https://example.com/profiles/created"
            )
            ->withHeader(
                "accept",
                "application/vnd.api+json;profile=https://example.com/profiles/created"
            );

        $this->assertEquals(["https://example.com/profiles/created"], $request->getAppliedProfiles());
        $this->assertEquals(["https://example.com/profiles/created"], $request->getRequestedProfiles());
    }

    /**
     * @test
     */
    public function getResourceWhenEmpty(): void
    {
        $request = $this->createRequestWithJsonBody([]);

        $resource = $request->getResource();

        $this->assertNull($resource);
    }

    /**
     * @test
     */
    public function getResource(): void
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
    public function getResourceTypeWhenEmpty(): void
    {
        $request = $this->createRequestWithJsonBody([]);

        $type = $request->getResourceType();

        $this->assertNull($type);
    }

    /**
     * @test
     */
    public function getResourceType(): void
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
    public function getResourceIdWhenEmpty(): void
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
    public function getResourceId(): void
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
    public function getResourceAttributes(): void
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
    public function getResourceAttribute(): void
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
    public function hasToOneRelationshipWhenTrue(): void
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
    public function hasToOneRelationshipWhenFalse(): void
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
    public function getToOneRelationship(): void
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
        $type = $resourceIdentifier !== null ? $resourceIdentifier->getType() : "";
        $id = $resourceIdentifier !== null ? $resourceIdentifier->getId() : "";

        $this->assertEquals("human", $type);
        $this->assertEquals("1", $id);
    }

    /**
     * @test
     */
    public function getDeletingToOneRelationship(): void
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
    public function getToOneRelationshiWhenNotExists(): void
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
    public function hasToManyRelationshipWhenTrue(): void
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
    public function hasToManyRelationshipWhenFalse(): void
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
    public function getToManyRelationship(): void
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
    public function getToManyRelationshipWhenNotExists(): void
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
    public function withQueryParamsInvalidatesParsedJsonApiQueryParams(): void
    {
        $request = $this->createRequestWithQueryParams(
            [
                "fields" => ["book" => "title,pages"],
                "include" => "authors",
                "page" => ["offset" => 0, "limit" => 10],
                "filter" => ["title" => "Working Effectively with Unit Tests"],
                "sort" => "title",
                "profile" => "https://example.com/profiles/last-modified",
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
                "profile" => "https://example.com/profiles/created",
            ]
        );

        $this->assertEquals(["isbn"], $request->getIncludedFields("book"));
        $this->assertEquals(["publisher"], $request->getIncludedRelationships(""));
        $this->assertEquals(["number" => 1, "size" => 10], $request->getPagination());
        $this->assertEquals(["title" => "Building Microservices"], $request->getFiltering());
        $this->assertEquals(["isbn"], $request->getSorting());
        $this->assertEquals(["https://example.com/profiles/created"], $request->getRequiredProfiles());
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

    private function createRequestWithHeader(string $headerName, string $headerValue): JsonApiRequest
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
