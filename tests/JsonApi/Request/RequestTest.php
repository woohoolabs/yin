<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Request;

use Exception;
use PHPUnit\Framework\TestCase;
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

class RequestTest extends TestCase
{
    /**
     * @test
     */
    public function validateJsonApiContentTypeHeader()
    {
        $this->assertValidContentTypeHeader("application/vnd.api+json");
    }

    /**
     * @test
     */
    public function validateJsonApiContentTypeHeaderWithSemicolon()
    {
        $this->assertValidContentTypeHeader("application/vnd.api+json;");
    }

    /**
     * @test
     */
    public function validateEmptyContentTypeHeader()
    {
        $this->assertValidContentTypeHeader("");
    }

    /**
     * @test
     */
    public function validateHtmlContentTypeHeader()
    {
        $this->assertValidContentTypeHeader("text/html; charset=utf-8");
    }

    /**
     * @test
     */
    public function validateMultipleMediaTypeContentTypeHeader()
    {
        $this->assertValidContentTypeHeader("application/vnd.api+json, text/*;q=0.3, text/html;q=0.7");
    }

    /**
     * @test
     */
    public function validateCaseInsensitiveContentTypeHeader()
    {
        $this->assertValidContentTypeHeader("Application/vnd.Api+JSON, text/*;q=0.3, text/html;Q=0.7");
    }

    /**
     * @test
     */
    public function validateInvalidContentTypeHeaderWithExtMediaType()
    {
        $this->expectException(MediaTypeUnsupported::class);
        $this->assertInvalidContentTypeHeader('application/vnd.api+json; ext="ext1,ext2"');
    }

    /**
     * @test
     */
    public function validateInvalidContentTypeHeaderWithWhitespaceBeforeParameter()
    {
        $this->expectException(MediaTypeUnsupported::class);
        $this->assertInvalidContentTypeHeader('application/vnd.api+json ; ext="ext1,ext2"');
    }

    /**
     * @test
     */
    public function validateInvalidContentTypeHeaderWithCharsetMediaType()
    {
        $this->expectException(MediaTypeUnsupported::class);
        $this->assertInvalidContentTypeHeader("application/vnd.api+json; Charset=utf-8");
    }

    private function assertValidContentTypeHeader($value)
    {
        try {
            $this->createRequestWithHeader("Content-Type", $value)->validateContentTypeHeader();
        } catch (Exception $e) {
            $this->fail("No exception should have been thrown, but the following was caught: " . $e->getMessage());
        }
    }

    private function assertInvalidContentTypeHeader($value)
    {
        $this->createRequestWithHeader("Content-Type", $value)->validateContentTypeHeader();
    }

    public function testValidateJsonApiAcceptHeaderWithExtMediaType()
    {
        try {
            $this->createRequestWithHeader("Accept", "application/vnd.api+json")->validateAcceptHeader();
        } catch (Exception $e) {
            $this->fail("No exception should have been thrown, but the following was caught: " . $e->getMessage());
        }
    }

    /**
     * @test
     */
    public function validateJsonApiAcceptHeaderWithAdditionalMediaTypes()
    {
        $this->expectException(MediaTypeUnacceptable::class);
        $this->createRequestWithHeader(
            "Accept",
            'application/vnd.api+json; ext="ext1,ext2"; charset=utf-8; lang=en'
        )->validateAcceptHeader();
    }

    public function testValidateEmptyQueryParams()
    {
        $queryParams = [];

        $this->assertValidQueryParams($queryParams);
    }

    /**
     * @test
     */
    public function validateBasicQueryParams()
    {
        $queryParams = [
            "fields" => ["user" => "name, address"],
            "include" => ["contacts"],
            "sort" => ["-name"],
            "page" => ["number" => "1"],
            "filter" => ["age" => "21"]
        ];

        $this->assertValidQueryParams($queryParams);
    }

    /**
     * @test
     */
    public function validateInvalidQueryParams()
    {
        $queryParams = [
            "fields" => ["user" => "name, address"],
            "paginate" => ["-name"]
        ];

        $this->expectException(QueryParamUnrecognized::class);
        $this->createRequestWithQueryParams($queryParams)->validateQueryParams();
    }

    private function assertValidQueryParams(array $params)
    {
        try {
            $this->createRequestWithQueryParams($params)->validateQueryParams();
        } catch (Exception $e) {
            $this->fail("No exception should have been thrown, but the following was catched: " . $e->getMessage());
        }
    }

    /**
     * @test
     */
    public function getEmptyIncludedFields()
    {
        $resourceType = "";
        $includedFields = [];
        $queryParams = [];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertEquals($includedFields, $request->getIncludedFields($resourceType));
    }

    /**
     * @test
     */
    public function getIncludedFieldsForResource()
    {
        $resourceType = "book";
        $includedFields = ["title", "pages"];
        $queryParams = ["fields" => ["book" => implode(",", $includedFields)]];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertEquals($includedFields, $request->getIncludedFields($resourceType));
    }

    /**
     * @test
     */
    public function getIncludedFieldsForUnspecifiedResource()
    {
        $resourceType = "newspaper";
        $includedFields = ["title", "pages"];
        $queryParams = ["fields" => ["book" => implode(",", $includedFields)]];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertEquals([], $request->getIncludedFields($resourceType));
    }

    /**
     * @test
     */
    public function getIncludedFieldWhenMalformed()
    {
        $this->expectException(QueryParamMalformed::class);

        $queryParams = ["fields" => ""];

        $request = $this->createRequestWithQueryParams($queryParams);
        $request->getIncludedFields("");
    }

    /**
     * @test
     */
    public function getIncludedFieldWhenFieldMalformed()
    {
        $this->expectException(QueryParamMalformed::class);

        $queryParams = ["fields" => ["book" => []]];

        $request = $this->createRequestWithQueryParams($queryParams);
        $request->getIncludedFields("");
    }

    /**
     * @test
     */
    public function isIncludedFieldWhenAllFieldsRequested()
    {
        $resourceType = "book";
        $field = "title";
        $queryParams = ["fields" => []];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertTrue($request->isIncludedField($resourceType, $field));

        $resourceType = "book";
        $field = "title";
        $queryParams = [];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertTrue($request->isIncludedField($resourceType, $field));
    }

    /**
     * @test
     */
    public function isIncludedFieldWhenNoFieldRequested()
    {
        $resourceType = "book";
        $field = "title";
        $queryParams = ["fields" => ["book" => ""]];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertFalse($request->isIncludedField($resourceType, $field));
    }

    /**
     * @test
     */
    public function isIncludedFieldWhenGivenFieldIsSpecified()
    {
        $resourceType = "book";
        $field = "title";
        $queryParams = ["fields" => ["book" => "title,pages"]];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertTrue($request->isIncludedField($resourceType, $field));
    }

    /**
     * @test
     */
    public function hasIncludedRelationshipsWhenTrue()
    {
        $queryParams = ["include" => "authors"];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertTrue($request->hasIncludedRelationships());
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
        $sorting = ["name", "age", "sex"];
        $queryParams = ["sort" => implode(",", $sorting)];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertEquals($sorting, $request->getSorting());
    }

    /**
     * @test
     */
    public function getSortingWhenMalformed()
    {
        $this->expectException(QueryParamMalformed::class);

        $queryParams = ["sort" => ["name" => "asc"]];

        $request = $this->createRequestWithQueryParams($queryParams);
        $request->getSorting();
    }

    /**
     * @test
     */
    public function getPaginationWhenEmpty()
    {
        $pagination = [];
        $queryParams = ["page" => ""];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertEquals($pagination, $request->getPagination());
    }

    /**
     * @test
     */
    public function getPaginationWhenNotEmpty()
    {
        $pagination = ["number" => "1", "size" => "10"];
        $queryParams = ["page" => $pagination];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertEquals($pagination, $request->getPagination());
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
        $queryParams = ["filter" => []];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertEquals([], $request->getFiltering());
    }

    /**
     * @test
     */
    public function getFilteringWhenNotEmpty()
    {
        $queryParams = ["filter" => ["name" => "John"]];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertEquals(["name" => "John"], $request->getFiltering());
    }

    /**
     * @test
     */
    public function getFilteringParam()
    {
        $queryParams = ["filter" => ["name" => "John"]];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertEquals("John", $request->getFilteringParam("name"));
    }

    /**
     * @test
     */
    public function getDefaultFilteringParamWhenNotFound()
    {
        $queryParams = ["filter" => ["name" => "John"]];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertFalse($request->getFilteringParam("age", false));
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
          "data" => []
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
                "type" => "user"
            ]
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
            "data" => []
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
                "id" => "1"
            ]
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
                    "name" => "Hot dog"
                ]
            ]
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
                    "name" => "Hot dog"
                ]
            ]
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
                        "data" => ["type" => "human", "id" => "1"]
                    ]
                ]
            ]
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
                        "data" => null
                    ]
                ]
            ]
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
                ]
            ]
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
                            ["type" => "dog", "id" => "3"]
                        ]
                    ]
                ]
            ]
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
                ]
            ]
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
    public function getParsedBody()
    {
        $parsedBody = [
            "data" => [
                "type" => "cat",
                "id" => "tom"
            ]
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

    private function createRequest(): Request
    {
        return new Request(new ServerRequest(), new DefaultExceptionFactory(), new JsonDeserializer());
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
