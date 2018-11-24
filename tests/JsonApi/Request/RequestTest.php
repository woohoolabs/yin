<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Request;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;
use WoohooLabs\Yin\JsonApi\Exception\MediaTypeUnacceptable;
use WoohooLabs\Yin\JsonApi\Exception\MediaTypeUnsupported;
use WoohooLabs\Yin\JsonApi\Exception\QueryParamMalformed;
use WoohooLabs\Yin\JsonApi\Exception\QueryParamUnrecognized;
use WoohooLabs\Yin\JsonApi\Request\Pagination\CursorBasedPagination;
use WoohooLabs\Yin\JsonApi\Request\Pagination\FixedPageBasedPagination;
use WoohooLabs\Yin\JsonApi\Request\Pagination\OffsetBasedPagination;
use WoohooLabs\Yin\JsonApi\Request\Pagination\PageBasedPagination;
use WoohooLabs\Yin\JsonApi\Request\Request;
use WoohooLabs\Yin\JsonApi\Serializer\JsonDeserializer;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Stream;
use Zend\Diactoros\Uri;

class RequestTest extends TestCase
{
    /**
     * @test
     */
    public function validateJsonApiContentTypeHeader()
    {
        $request = $this->createRequestWithHeader("Content-Type", "application/vnd.api+json");

        $request->validateContentTypeHeader();

        $this->addToAssertionCount(1);
    }

    /**
     * @test
     */
    public function validateJsonApiContentTypeHeaderWithSemicolon()
    {
        $request = $this->createRequestWithHeader("Content-Type", "application/vnd.api+json;");

        $request->validateContentTypeHeader();

        $this->addToAssertionCount(1);
    }

    /**
     * @test
     */
    public function validateEmptyContentTypeHeader()
    {
        $request = $this->createRequestWithHeader("Content-Type", "");

        $request->validateContentTypeHeader();

        $this->addToAssertionCount(1);
    }

    /**
     * @test
     */
    public function validateHtmlContentTypeHeader()
    {
        $request = $this->createRequestWithHeader("Content-Type", "text/html; charset=utf-8");

        $request->validateContentTypeHeader();

        $this->addToAssertionCount(1);
    }

    /**
     * @test
     */
    public function validateMultipleMediaTypeContentTypeHeader()
    {
        $request = $this->createRequestWithHeader("Content-Type", "application/vnd.api+json, text/*;q=0.3, text/html;q=0.7");

        $request->validateContentTypeHeader();

        $this->addToAssertionCount(1);
    }

    /**
     * @test
     */
    public function validateCaseInsensitiveContentTypeHeader()
    {
        $request = $this->createRequestWithHeader("Content-Type", "Application/vnd.Api+JSON, text/*;q=0.3, text/html;Q=0.7");

        $request->validateContentTypeHeader();

        $this->addToAssertionCount(1);
    }

    /**
     * @test
     */
    public function validateInvalidContentTypeHeaderWithExtMediaType()
    {
        $request = $this->createRequestWithHeader("Content-Type", 'application/vnd.api+json; ext="ext1,ext2"');

        $this->expectException(MediaTypeUnsupported::class);

        $request->validateContentTypeHeader();
    }

    /**
     * @test
     */
    public function validateInvalidContentTypeHeaderWithWhitespaceBeforeParameter()
    {
        $request = $this->createRequestWithHeader("Content-Type", 'application/vnd.api+json ; ext="ext1,ext2"');

        $this->expectException(MediaTypeUnsupported::class);

        $request->validateContentTypeHeader();
    }

    /**
     * @test
     */
    public function validateInvalidContentTypeHeaderWithCharsetMediaType()
    {
        $request = $this->createRequestWithHeader("Content-Type", "application/vnd.api+json; Charset=utf-8");

        $this->expectException(MediaTypeUnsupported::class);

        $request->validateContentTypeHeader();
    }

    public function testValidateJsonApiAcceptHeaderWithExtMediaType()
    {
        $request = $this->createRequestWithHeader("Accept", "application/vnd.api+json");

        $request->validateAcceptHeader();

        $this->addToAssertionCount(1);
    }

    /**
     * @test
     */
    public function validateJsonApiAcceptHeaderWithAdditionalMediaTypes()
    {
        $request = $this->createRequestWithHeader("Accept", 'application/vnd.api+json; ext="ext1,ext2"; charset=utf-8; lang=en');

        $this->expectException(MediaTypeUnacceptable::class);

        $request->validateAcceptHeader();
    }

    public function testValidateEmptyQueryParams()
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
    public function getFixedPageBasedPagination()
    {
        $pagination = new FixedPageBasedPagination(1);
        $queryParams = ["page" => ["number" => $pagination->getPage()]];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertEquals($pagination, $request->getFixedPageBasedPagination());
    }

    /**
     * @test
     */
    public function getPageBasedPagination()
    {
        $pagination = new PageBasedPagination(1, 10);
        $queryParams = ["page" => ["number" => $pagination->getPage(), "size" => $pagination->getSize()]];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertEquals($pagination, $request->getPageBasedPagination());
    }

    /**
     * @test
     */
    public function getOffsetBasedPagination()
    {
        $pagination = new OffsetBasedPagination(1, 10);
        $queryParams = ["page" => ["offset" => $pagination->getOffset(), "limit" => $pagination->getLimit()]];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertEquals($pagination, $request->getOffsetBasedPagination());
    }

    /**
     * @test
     */
    public function getCursorBasedPagination()
    {
        $pagination = new CursorBasedPagination("abcdefg");
        $queryParams = ["page" => ["cursor" => $pagination->getCursor()]];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertEquals($pagination, $request->getCursorBasedPagination());
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
        $request = $this->createRequestWithHeader("Content-Type", "application/vnd.api+json");

        $profiles = $request->getAppliedProfiles();

        $this->assertEmpty($profiles);
    }

    /**
     * @test
     */
    public function getAppliedProfilesWhenOneProfile()
    {
        $request = $this->createRequestWithHeader(
            "Content-Type",
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
            "Content-Type",
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
            "Content-Type",
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
            "Content-Type",
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
            "Content-Type",
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
        $request = $this->createRequestWithHeader("Accept", "application/vnd.api+json");

        $profiles = $request->getRequestedProfiles();

        $this->assertEmpty($profiles);
    }

    /**
     * @test
     */
    public function getRequestedProfilesWhenTwoProfiles()
    {
        $request = $this->createRequestWithHeader(
            "Accept",
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
            "Accept",
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
            "Accept",
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
    public function getQueryParamWhenNotFound()
    {
        $queryParams = [];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertEquals("xyz", $request->getQueryParam("a_b", "xyz"));
    }

    /**
     * @test
     */
    public function getQueryParamWhenNotEmpty()
    {
        $queryParamName = "abc";
        $queryParamValue = "cde";
        $queryParams = [$queryParamName => $queryParamValue];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertEquals($queryParamValue, $request->getQueryParam($queryParamName));
    }

    /**
     * @test
     */
    public function withQueryParam()
    {
        $queryParams = [];
        $addedQueryParamName = "abc";
        $addedQueryParamValue = "def";

        $request = $this->createRequestWithQueryParams($queryParams);
        $newRequest = $request->withQueryParam($addedQueryParamName, $addedQueryParamValue);
        $this->assertNull($request->getQueryParam($addedQueryParamName));
        $this->assertEquals($addedQueryParamValue, $newRequest->getQueryParam($addedQueryParamName));
    }

    /**
     * @test
     */
    public function getResourceWhenEmpty()
    {
        $body = [];

        $request = $this->createRequestWithJsonBody($body);
        $this->assertNull($request->getResource());
    }

    /**
     * @test
     */
    public function getResource()
    {
        $body = [
          "data" => [],
        ];

        $request = $this->createRequestWithJsonBody($body);
        $this->assertEquals($body["data"], $request->getResource());
    }

    /**
     * @test
     */
    public function getResourceTypeWhenEmpty()
    {
        $body = [];

        $request = $this->createRequestWithJsonBody($body);
        $this->assertNull($request->getResourceType());
    }

    /**
     * @test
     */
    public function getResourceType()
    {
        $body = [
            "data" => [
                "type" => "user",
            ],
        ];

        $request = $this->createRequestWithJsonBody($body);
        $this->assertEquals($body["data"]["type"], $request->getResourceType());
    }

    /**
     * @test
     */
    public function getResourceIdWhenEmpty()
    {
        $body = [
            "data" => [],
        ];

        $request = $this->createRequestWithJsonBody($body);
        $this->assertNull($request->getResourceId());
    }

    /**
     * @test
     */
    public function getResourceId()
    {
        $body = [
            "data" => [
                "id" => "1",
            ],
        ];

        $request = $this->createRequestWithJsonBody($body);
        $this->assertEquals($body["data"]["id"], $request->getResourceId());
    }

    /**
     * @test
     */
    public function getResourceAttributes()
    {
        $body = [
            "data" => [
                "type" => "dog",
                "id" => "1",
                "attributes" => [
                    "name" => "Hot dog",
                ],
            ],
        ];

        $request = $this->createRequestWithJsonBody($body);
        $this->assertEquals($body["data"]["attributes"], $request->getResourceAttributes());
    }

    /**
     * @test
     */
    public function getResourceAttribute()
    {
        $body = [
            "data" => [
                "type" => "dog",
                "id" => "1",
                "attributes" => [
                    "name" => "Hot dog",
                ],
            ],
        ];

        $request = $this->createRequestWithJsonBody($body);
        $this->assertEquals("Hot dog", $request->getResourceAttribute("name"));
    }

    /**
     * @test
     */
    public function getToOneRelationship()
    {
        $body = [
            "data" => [
                "type" => "dog",
                "id" => "1",
                "relationships" => [
                    "owner" => [
                        "data" => ["type" => "human", "id" => "1"],
                    ],
                ],
            ],
        ];

        $request = $this->createRequestWithJsonBody($body);
        $resourceIdentifier = $request->getToOneRelationship("owner")->getResourceIdentifier();
        $this->assertEquals("human", $resourceIdentifier->getType());
        $this->assertEquals("1", $resourceIdentifier->getId());
    }

    /**
     * @test
     */
    public function getDeletingToOneRelationship()
    {
        $body = [
            "data" => [
                "type" => "dog",
                "id" => "1",
                "relationships" => [
                    "owner" => [
                        "data" => null,
                    ],
                ],
            ],
        ];

        $request = $this->createRequestWithJsonBody($body);
        $relationship = $request->getToOneRelationship("owner");
        $this->assertTrue($relationship->isEmpty());
    }

    /**
     * @test
     */
    public function getNullWhenToOneRelationshipNotExists()
    {
        $body = [
            "data" => [
                "type" => "dog",
                "id" => "1",
                "relationships" => [
                ],
            ],
        ];

        $request = $this->createRequestWithJsonBody($body);
        $this->assertNull($request->getToOneRelationship("owner"));
    }

    /**
     * @test
     */
    public function getToManyRelationship()
    {
        $body = [
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
        ];

        $request = $this->createRequestWithJsonBody($body);
        $resourceIdentifiers = $request->getToManyRelationship("friends")->getResourceIdentifiers();
        $this->assertEquals("dog", $resourceIdentifiers[0]->getType());
        $this->assertEquals("2", $resourceIdentifiers[0]->getId());
        $this->assertEquals("dog", $resourceIdentifiers[1]->getType());
        $this->assertEquals("3", $resourceIdentifiers[1]->getId());
    }

    /**
     * @test
     */
    public function getNullWhenToManyRelationshipNotExists()
    {
        $body = [
            "data" => [
                "type" => "dog",
                "id" => "1",
                "relationships" => [
                ],
            ],
        ];

        $request = $this->createRequestWithJsonBody($body);
        $this->assertNull($request->getToManyRelationship("friends"));
    }

    /**
     * @test
     */
    public function getProtocolVersion()
    {
        $protocolVersion = "2";

        $request = $this->createRequest()->withProtocolVersion($protocolVersion);
        $this->assertEquals($protocolVersion, $request->getProtocolVersion());
    }

    /**
     * @test
     */
    public function getHeaders()
    {
        $header1Name = "a";
        $header1Value = "b";
        $header2Name = "c";
        $header2Value = "d";
        $headers = [$header1Name => [$header1Value], $header2Name => [$header2Value]];

        $request = $this->createRequestWithHeader($header1Name, $header1Value)->withHeader($header2Name, $header2Value);
        $this->assertEquals($headers, $request->getHeaders());
    }

    /**
     * @test
     */
    public function hasHeaderWhenHeaderNotExists()
    {
        $request = $this->createRequestWithHeader("a", "b");

        $this->assertFalse($request->hasHeader("c"));
    }

    /**
     * @test
     */
    public function hasHeaderWhenHeaderExists()
    {
        $request = $this->createRequestWithHeader("a", "b");

        $this->assertTrue($request->hasHeader("a"));
    }

    /**
     * @test
     */
    public function getHeaderWhenHeaderExists()
    {
        $request = $this->createRequestWithHeader("a", "b");

        $this->assertEquals(["b"], $request->getHeader("a"));
    }

    /**
     * @test
     */
    public function getHeaderLineWhenHeaderNotExists()
    {
        $request = $this->createRequestWithHeaders(["a" => ["b", "c", "d"]]);

        $this->assertEquals("", $request->getHeaderLine("b"));
    }

    /**
     * @test
     */
    public function getHeaderLineWhenHeaderExists()
    {
        $request = $this->createRequestWithHeaders(["a" => ["b", "c", "d"]]);

        $this->assertEquals("b,c,d", $request->getHeaderLine("a"));
    }

    /**
     * @test
     */
    public function withHeader()
    {
        $headers = [];
        $headerName = "a";
        $headerValue = "b";

        $request = $this->createRequestWithHeaders($headers);
        $newRequest = $request->withHeader($headerName, $headerValue);
        $this->assertEquals([], $request->getHeader($headerName));
        $this->assertEquals([$headerValue], $newRequest->getHeader($headerName));
    }

    /**
     * @test
     */
    public function withAddedHeader()
    {
        $headerName = "a";
        $headerValue = "b";
        $headers = [$headerName => $headerValue];

        $request = $this->createRequestWithHeaders($headers);
        $newRequest = $request->withAddedHeader($headerName, $headerValue);
        $this->assertEquals([$headerValue], $request->getHeader($headerName));
        $this->assertEquals([$headerValue, $headerValue], $newRequest->getHeader($headerName));
    }

    /**
     * @test
     */
    public function withoutHeader()
    {
        $headerName = "a";
        $headerValue = "b";
        $headers = [$headerName => $headerValue];

        $request = $this->createRequestWithHeaders($headers);
        $newRequest = $request->withoutHeader($headerName);

        $this->assertEquals([$headerValue], $request->getHeader($headerName));
        $this->assertEquals([], $newRequest->getHeader($headerName));
    }

    /**
     * @test
     */
    public function getBody()
    {
        $body = new Stream("php://input");

        $serverRequest = $this->createMock(ServerRequestInterface::class);
        $serverRequest->expects($this->once())
            ->method("getBody")
            ->will($this->returnValue($body));

        $request = $this->createRequest($serverRequest);

        $this->assertEquals($body, $request->getBody());
    }

    /**
     * @test
     */
    public function withBody()
    {
        $body = new Stream("php://input");

        $request = $this->createRequest();
        $request = $request->withBody($body);

        $this->assertEquals($body, $request->getBody());
    }

    /**
     * @test
     */
    public function getRequestTarget()
    {
        $serverRequest = $this->createMock(ServerRequestInterface::class);
        $serverRequest->expects($this->once())
            ->method("getRequestTarget")
            ->will($this->returnValue("/abc"));

        $request = $this->createRequest($serverRequest);

        $this->assertEquals("/abc", $request->getRequestTarget());
    }

    /**
     * @test
     */
    public function withRequestTarget()
    {
        $request = $this->createRequest();

        $request = $request->withRequestTarget("/abc");

        $this->assertEquals("/abc", $request->getRequestTarget());
    }

    /**
     * @test
     */
    public function getMethod()
    {
        $method = "PUT";

        $request = $this->createRequest();
        $newRequest = $request->withMethod($method);
        $this->assertEquals("GET", $request->getMethod());
        $this->assertEquals($method, $newRequest->getMethod());
    }

    /**
     * @test
     */
    public function getUri()
    {
        $uri = new Uri();

        $serverRequest = $this->createMock(ServerRequestInterface::class);
        $serverRequest->expects($this->once())
            ->method("getUri")
            ->will($this->returnValue($uri));

        $request = $this->createRequest($serverRequest);

        $this->assertEquals($uri, $request->getUri());
    }

    /**
     * @test
     */
    public function withUri()
    {
        $request = $this->createRequest();

        $request = $request->withUri(new Uri("https://example.com"));

        $this->assertEquals("https://example.com", $request->getUri()->__toString());
    }

    /**
     * @test
     */
    public function getServerParams()
    {
        $serverRequest = $this->createMock(ServerRequestInterface::class);
        $serverRequest->expects($this->once())
            ->method("getServerParams")
            ->will($this->returnValue(["abc" => "def"]));

        $request = $this->createRequest($serverRequest);

        $this->assertEquals(["abc" => "def"], $request->getServerParams());
    }

    /**
     * @test
     */
    public function getCookieParams()
    {
        $serverRequest = $this->createMock(ServerRequestInterface::class);
        $serverRequest->expects($this->once())
            ->method("getCookieParams")
            ->will($this->returnValue(["abc" => "def"]));

        $request = $this->createRequest($serverRequest);

        $this->assertEquals(["abc" => "def"], $request->getCookieParams());
    }

    /**
     * @test
     */
    public function withCookieParams()
    {
        $request = $this->createRequest();

        $request = $request->withCookieParams(["abc" => "def"]);

        $this->assertEquals(["abc" => "def"], $request->getCookieParams());
    }

    /**
     * @test
     */
    public function getUploadedFiles()
    {
        $serverRequest = $this->createMock(ServerRequestInterface::class);
        $serverRequest->expects($this->once())
            ->method("getUploadedFiles")
            ->will($this->returnValue(["abc"]));

        $request = $this->createRequest($serverRequest);

        $this->assertEquals(["abc"], $request->getUploadedFiles());
    }

    /**
     * @test
     */
    public function getQueryParams()
    {
        $queryParamName = "abc";
        $queryParamValue = "cde";
        $queryParams = [$queryParamName => $queryParamValue];

        $request = $this->createRequest();
        $newRequest = $request->withQueryParams($queryParams);
        $this->assertEquals([], $request->getQueryParams());
        $this->assertEquals($queryParams, $newRequest->getQueryParams());
    }

    /**
     * @test
     */
    public function withQueryParamsInitializesParsedJsonApiQueryParams()
    {
        $request = $this->createRequestWithQueryParams(
            [
                "fields" => ["book" => "title,pages"],
                "include" => "authors",
                "page" => ["offset" => 0, "limit" => 10],
                "filter" => ["name" => "John"],
            ]
        );

        $request->getIncludedFields("");
        $request->getIncludedRelationships("");
        $request->getPagination();
        $request->getFiltering();
        $request->getSorting();

        $request = $request->withQueryParams(
            [
                "fields" => ["book" => "isbn"],
                "include" => "publisher",
                "page" => ["number" => 1, "size" => 10],
                "filter" => ["name" => "Jane"],
            ]
        );

        $this->assertEquals(["isbn"], $request->getIncludedFields("book"));
        $this->assertEquals(["publisher"], $request->getIncludedRelationships(""));
        $this->assertEquals(["number" => 1, "size" => 10], $request->getPagination());
        $this->assertEquals(["name" => "Jane"], $request->getFiltering());
    }

    /**
     * @test
     */
    public function getParsedBody()
    {
        $parsedBody = [
            "data" => [
                "type" => "cat",
                "id" => "tom",
            ],
        ];

        $request = $this->createRequest();
        $newRequest = $request->withParsedBody($parsedBody);
        $this->assertEquals(null, $request->getParsedBody());
        $this->assertEquals($parsedBody, $newRequest->getParsedBody());
    }

    /**
     * @test
     */
    public function getAttributes()
    {
        $attribute1Key = "a";
        $attribute1Value = true;
        $attribute2Key = "b";
        $attribute2Value = 123456;
        $attributes = [$attribute1Key => $attribute1Value, $attribute2Key => $attribute2Value];

        $request = $this->createRequest();
        $newRequest = $request
            ->withAttribute($attribute1Key, $attribute1Value)
            ->withAttribute($attribute2Key, $attribute2Value);

        $this->assertEquals([], $request->getAttributes());
        $this->assertEquals($attributes, $newRequest->getAttributes());
        $this->assertEquals($attribute1Value, $newRequest->getAttribute($attribute1Key));
    }

    /**
     * @test
     */
    public function withoutAttributes()
    {
        $request = $this->createRequest();
        $newRequest = $request
            ->withAttribute("abc", "cde")
            ->withoutAttribute("abc");

        $this->assertEquals([], $request->getAttributes());
        $this->assertEmpty($newRequest->getAttributes());
    }

    private function createRequest(ServerRequestInterface $serverRequest = null): Request
    {
        return new Request(
            $serverRequest ?? new ServerRequest(),
            new DefaultExceptionFactory(),
            new JsonDeserializer()
        );
    }

    private function createRequestWithJsonBody(array $body): Request
    {
        $psrRequest = new ServerRequest();
        $psrRequest = $psrRequest->withParsedBody($body);

        return new Request($psrRequest, new DefaultExceptionFactory(), new JsonDeserializer());
    }

    private function createRequestWithHeaders(array $headers): Request
    {
        $psrRequest = new ServerRequest([], [], null, null, "php://temp", $headers);

        return new Request($psrRequest, new DefaultExceptionFactory(), new JsonDeserializer());
    }

    private function createRequestWithHeader($headerName, $headerValue): Request
    {
        $psrRequest = new ServerRequest([], [], null, null, "php://temp", [$headerName => $headerValue]);

        return new Request($psrRequest, new DefaultExceptionFactory(), new JsonDeserializer());
    }

    private function createRequestWithQueryParams(array $queryParams): Request
    {
        $psrRequest = new ServerRequest();
        $psrRequest = $psrRequest->withQueryParams($queryParams);

        return new Request($psrRequest, new DefaultExceptionFactory(), new JsonDeserializer());
    }
}
