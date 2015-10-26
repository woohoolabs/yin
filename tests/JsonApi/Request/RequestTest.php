<?php
namespace WoohooLabsTest\Yin\JsonApi\Request;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Request\Pagination\CursorPagination;
use WoohooLabs\Yin\JsonApi\Request\Pagination\FixedPagePagination;
use WoohooLabs\Yin\JsonApi\Request\Pagination\OffsetPagination;
use WoohooLabs\Yin\JsonApi\Request\Pagination\PagePagination;
use WoohooLabs\Yin\JsonApi\Request\Request;
use Zend\Diactoros\ServerRequest as DiactorosRequest;

class RequestTest extends PHPUnit_Framework_TestCase
{
    public function testValidateJsonApiContentTypeHeader()
    {
        $this->assertValidContentTypeHeader("application/vnd.api+json");
    }

    public function testValidateEmptyContentTypeHeader()
    {
        $this->assertValidContentTypeHeader("");
    }

    public function testValidateHtmlContentTypeHeader()
    {
        $this->assertValidContentTypeHeader("text/html; charset=utf-8");
    }

    public function testValidateContentTypeHeaderWithSupportedExtensionMediaType()
    {
        $this->assertValidContentTypeHeader('application/vnd.api+json; supported-ext="bulk,jsonpatch"');
    }

    public function testValidateValidContentTypeHeaderWithExtMediaType()
    {
        $this->assertValidContentTypeHeader('application/vnd.api+json; ext="ext1,ext2"');
    }

    public function testValidateContentTypeHeaderWithExtensionMediaTypes()
    {
        $this->assertValidContentTypeHeader('application/vnd.api+json; ext="ext1,ext2"; supported-ext="ext1,ext2"');
    }

    /**
     * @expectedException \WoohooLabs\Yin\JsonApi\Exception\MediaTypeUnsupported
     */
    public function testValidateContentTypeHeaderWithCharsetMediaType()
    {
        $this->assertInvalidContentTypeHeader("application/vnd.api+json; charset=utf-8");
    }

    /**
     * @expectedException \WoohooLabs\Yin\JsonApi\Exception\MediaTypeUnsupported
     */
    public function testValidateContentTypeHeaderWithMultipleMediaTypes()
    {
        $this->assertInvalidContentTypeHeader("application/vnd.api+json; charset=utf-8; lang=en");
    }

    private function assertValidContentTypeHeader($value)
    {
        try {
            $this->createRequestWithHeader("Content-Type", $value)->validateContentTypeHeader();
        } catch (\Exception $e) {
            $this->fail("No exception should have been thrown, but the following was catched: " . $e->getMessage());
        }
    }

    private function assertInvalidContentTypeHeader($value)
    {
        $this->createRequestWithHeader("Content-Type", $value)->validateContentTypeHeader();
    }

    public function testValidateJsonApiAcceptHeaderWithExtMediaType()
    {
        $this->assertValidAcceptHeader('application/vnd.api+json; ext="ext1,ext2"');
    }

    /**
     * @expectedException \WoohooLabs\Yin\JsonApi\Exception\MediaTypeUnacceptable
     */
    public function testValidateJsonApiAcceptHeaderWithAdditionalMediaTypes()
    {
        $this->assertInvalidAcceptHeader('application/vnd.api+json; ext="ext1,ext2"; charset=utf-8; lang=en');
    }

    private function assertValidAcceptHeader($value)
    {
        try {
            $this->createRequestWithHeader("Accept", $value)->validateAcceptHeader();
        } catch (\Exception $e) {
            $this->fail("No exception should have been thrown, but the following was catched: " . $e->getMessage());
        }
    }

    private function assertInvalidAcceptHeader($value)
    {
        $this->createRequestWithHeader("Accept", $value)->validateAcceptHeader();
    }

    public function testValidateEmptyQueryParams()
    {
        $queryParams = [];

        $this->assertValidQueryParams($queryParams);
    }

    public function testValidateBasicQueryParams()
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
     * @expectedException \WoohooLabs\Yin\JsonApi\Exception\QueryParamUnrecognized
     */
    public function testValidateInvalidQueryParams()
    {
        $queryParams = [
            "fields" => ["user" => "name, address"],
            "paginate" => ["-name"]
        ];

        $this->createRequestWithQueryParams($queryParams)->validateQueryParams();
    }

    private function assertValidQueryParams(array $params)
    {
        try {
            $this->createRequestWithQueryParams($params)->validateQueryParams();
        } catch (\Exception $e) {
            $this->fail("No exception should have been thrown, but the following was catched: " . $e->getMessage());
        }
    }

    public function testGetExtensions()
    {
        $extensions = ["ext1", "ext2", "ext3"];
        $contentType = 'application/vnd.api+json; ext="' . implode(",", $extensions) . '"';

        $request = $this->createRequestWithHeader("Content-Type", $contentType);
        $this->assertEquals($extensions, $request->getExtensions());
    }

    public function testGetRequiredExtensions()
    {
        $extensions = ["ext1", "ext2", "ext3"];
        $accept = 'application/vnd.api+json; ext="' . implode(",", $extensions) . '"';

        $request = $this->createRequestWithHeader("Accept", $accept);
        $this->assertEquals($extensions, $request->getRequiredExtensions());
    }

    public function testGetEmptyIncludedFields()
    {
        $resourceType = "";
        $includedFields = [];
        $queryParams = [];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertEquals($includedFields, $request->getIncludedFields($resourceType));
    }

    public function testGetIncludedFieldsForResource()
    {
        $resourceType = "book";
        $includedFields = ["title", "pages"];
        $queryParams = ["fields" => ["book" => implode(",", $includedFields)]];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertEquals($includedFields, $request->getIncludedFields($resourceType));
    }

    public function testGetIncludedFieldsForUnspecifiedResource()
    {
        $resourceType = "newspaper";
        $includedFields = ["title", "pages"];
        $queryParams = ["fields" => ["book" => implode(",", $includedFields)]];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertEquals([], $request->getIncludedFields($resourceType));
    }

    public function testIsIncludedFieldWhenAllFieldsRequested()
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

    public function testIsIncludedFieldWhenNoFieldRequested()
    {
        $resourceType = "book";
        $field = "title";
        $queryParams = ["fields" => ["book" => ""]];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertFalse($request->isIncludedField($resourceType, $field));
    }

    public function testIsIncludedFieldWhenGivenFieldIsSpecified()
    {
        $resourceType = "book";
        $field = "title";
        $queryParams = ["fields" => ["book" => "title,pages"]];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertTrue($request->isIncludedField($resourceType, $field));
    }

    public function testHasIncludedRelationshipsWhenTrue()
    {
        $queryParams = ["include" => "authors"];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertTrue($request->hasIncludedRelationships());
    }

    public function testHasIncludedRelationshipsWhenFalse()
    {
        $queryParams = ["include" => ""];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertFalse($request->hasIncludedRelationships());

        $queryParams = [];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertFalse($request->hasIncludedRelationships());
    }

    public function testGetIncludedEmptyRelationshipsWhenEmpty()
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

    public function testGetIncludedRelationshipsForPrimaryResource()
    {
        $baseRelationshipPath = "";
        $includedRelationships = ["authors"];
        $queryParams = ["include" => implode(",", $includedRelationships)];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertEquals($includedRelationships, $request->getIncludedRelationships($baseRelationshipPath));
    }

    public function testGetIncludedRelationshipsForEmbeddedResource()
    {
        $baseRelationshipPath = "book";
        $includedRelationships = ["authors"];
        $queryParams = ["include" => "book,book.authors"];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertEquals($includedRelationships, $request->getIncludedRelationships($baseRelationshipPath));
    }

    public function testGetIncludedRelationshipsForMultipleEmbeddedResource()
    {
        $baseRelationshipPath = "book.authors";
        $includedRelationships = ["contacts", "address"];
        $queryParams = ["include" => "book,book.authors,book.authors.contacts,book.authors.address"];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertEquals($includedRelationships, $request->getIncludedRelationships($baseRelationshipPath));
    }

    public function testIsIncludedRelationshipForPrimaryResourceWhenEmpty()
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

    public function testIsIncludedRelationshipForPrimaryResourceWhenEmptyWithDefault()
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

    public function testIsIncludedRelationshipForPrimaryResourceWithDefault()
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

    public function testIsIncludedRelationshipForEmbeddedResource()
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

    public function testIsIncludedRelationshipForEmbeddedResourceWhenDefaulted()
    {
        $baseRelationshipPath = "authors";
        $requiredRelationship = "contacts";
        $defaultRelationships = ["contacts" => true];
        $queryParams = ["include" => ""];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertTrue(
            $request->isIncludedRelationship($baseRelationshipPath, $requiredRelationship, $defaultRelationships)
        );
    }

    public function testGetSortingWhenEmpty()
    {
        $sorting = [];
        $queryParams = ["sort" => ""];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertEquals($sorting, $request->getSorting());
    }

    public function testGetSortingWhenNotEmpty()
    {
        $sorting = ["name", "age", "sex"];
        $queryParams = ["sort" => implode(",", $sorting)];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertEquals($sorting, $request->getSorting());
    }

    public function testGetPaginationWhenEmpty()
    {
        $pagination = [];
        $queryParams = ["page" => ""];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertEquals($pagination, $request->getPagination());
    }

    public function testGetPaginationWhenNotEmpty()
    {
        $pagination = ["number" => "1", "size" => "10"];
        $queryParams = ["page" => $pagination];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertEquals($pagination, $request->getPagination());
    }

    public function testGetFixedPageBasedPagination()
    {
        $pagination = new FixedPagePagination(1);
        $queryParams = ["page" => ["number" => $pagination->getPage()]];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertEquals($pagination, $request->getFixedPageBasedPagination());
    }

    public function testGetPageBasedPagination()
    {
        $pagination = new PagePagination(1, 10);
        $queryParams = ["page" => ["number" => $pagination->getPage(), "size" => $pagination->getSize()]];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertEquals($pagination, $request->getPageBasedPagination());
    }

    public function testGetOffsetBasedPagination()
    {
        $pagination = new OffsetPagination(1, 10);
        $queryParams = ["page" => ["offset" => $pagination->getOffset(), "limit" => $pagination->getLimit()]];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertEquals($pagination, $request->getOffsetBasedPagination());
    }

    public function testGetCursorBasedPagination()
    {
        $pagination = new CursorPagination("abcdefg");
        $queryParams = ["page" => ["cursor" => $pagination->getCursor()]];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertEquals($pagination, $request->getCursorBasedPagination());
    }

    public function testGetFilteringWhenEmpty()
    {
        $filtering = [];
        $queryParams = ["filter" => $filtering];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertEquals($filtering, $request->getFiltering());
    }

    public function testGetFilteringWhenNotEmpty()
    {
        $filtering = ["name" => "John", "age" => "40", "sex" => "male"];
        $queryParams = ["filter" => $filtering];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertEquals($filtering, $request->getFiltering());
    }

    public function testGetQueryParamWhenNotFound()
    {
        $queryParams = [];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertEquals("xyz", $request->getQueryParam("a_b", "xyz"));
    }

    public function testGetQueryParamWhenNotEmpty()
    {
        $queryParamName = "abc";
        $queryParamValue = "cde";
        $queryParams = [$queryParamName => $queryParamValue];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertEquals($queryParamValue, $request->getQueryParam($queryParamName));
    }

    public function testWithQueryParam()
    {
        $queryParams = [];
        $addedQueryParamName = "abc";
        $addedQueryParamValue = "def";

        $request = $this->createRequestWithQueryParams($queryParams);
        $newRequest = $request->withQueryParam($addedQueryParamName, $addedQueryParamValue);
        $this->assertNull($request->getQueryParam($addedQueryParamName));
        $this->assertEquals($addedQueryParamValue, $newRequest->getQueryParam($addedQueryParamName));
    }

    public function testGetResourceWhenEmpty()
    {
        $body = [];

        $request = $this->createRequestWithJsonBody($body);
        $this->assertNull($request->getResource());
    }

    public function testGetResource()
    {
        $body = [
          "data" => []
        ];

        $request = $this->createRequestWithJsonBody($body);
        $this->assertEquals($body["data"], $request->getResource());
    }

    public function testGetResourceTypeWhenEmpty()
    {
        $body = [];

        $request = $this->createRequestWithJsonBody($body);
        $this->assertNull($request->getResourceType());
    }

    public function testGetResourceType()
    {
        $body = [
            "data" => [
                "type" => "user"
            ]
        ];

        $request = $this->createRequestWithJsonBody($body);
        $this->assertEquals($body["data"]["type"], $request->getResourceType());
    }

    public function testGetResourceIdWhenEmpty()
    {
        $body = [
            "data" => []
        ];

        $request = $this->createRequestWithJsonBody($body);
        $this->assertNull($request->getResourceId());
    }

    public function testGetResourceId()
    {
        $body = [
            "data" => [
                "id" => "1"
            ]
        ];

        $request = $this->createRequestWithJsonBody($body);
        $this->assertEquals($body["data"]["id"], $request->getResourceId());
    }

    public function testGetProtocolVersion()
    {
        $protocolVersion = "2";

        $request = $this->createRequest()->withProtocolVersion($protocolVersion);
        $this->assertEquals($protocolVersion, $request->getProtocolVersion());
    }

    public function testGetHeaders()
    {
        $header1Name = "a";
        $header1Value = "b";
        $header2Name = "c";
        $header2Value = "d";
        $headers = [$header1Name => [$header1Value], $header2Name => [$header2Value]];

        $request = $this->createRequestWithHeader($header1Name, $header1Value)->withHeader($header2Name, $header2Value);
        $this->assertEquals($headers, $request->getHeaders());
    }

    public function testHasHeaderWhenHeaderNotExists()
    {
        $request = $this->createRequestWithHeader("a", "b");

        $this->assertFalse($request->hasHeader("c"));
    }

    public function testHasHeaderWhenHeaderExists()
    {
        $request = $this->createRequestWithHeader("a", "b");

        $this->assertTrue($request->hasHeader("a"));
    }

    public function testGetHeaderWhenHeaderExists()
    {
        $request = $this->createRequestWithHeader("a", "b");

        $this->assertEquals(["b"], $request->getHeader("a"));
    }

    public function testGetHeaderLineWhenHeaderNotExists()
    {
        $request = $this->createRequestWithHeaders(["a" => ["b", "c", "d"]]);

        $this->assertEquals("", $request->getHeaderLine("b"));
    }

    public function testGetHeaderLineWhenHeaderExists()
    {
        $request = $this->createRequestWithHeaders(["a" => ["b", "c", "d"]]);

        $this->assertEquals("b,c,d", $request->getHeaderLine("a"));
    }

    public function testWithHeader()
    {
        $headers = [];
        $headerName = "a";
        $headerValue = "b";

        $request = $this->createRequestWithHeaders($headers);
        $newRequest = $request->withHeader($headerName, $headerValue);
        $this->assertEquals([], $request->getHeader($headerName));
        $this->assertEquals([$headerValue], $newRequest->getHeader($headerName));
    }

    public function testWithAddedHeader()
    {
        $headerName = "a";
        $headerValue = "b";
        $headers = [$headerName => $headerValue];

        $request = $this->createRequestWithHeaders($headers);
        $newRequest = $request->withAddedHeader($headerName, $headerValue);
        $this->assertEquals([$headerValue], $request->getHeader($headerName));
        $this->assertEquals([$headerValue, $headerValue], $newRequest->getHeader($headerName));
    }

    public function testWithoutHeader()
    {
        $headerName = "a";
        $headerValue = "b";
        $headers = [$headerName => $headerValue];

        $request = $this->createRequestWithHeaders($headers);
        $newRequest = $request->withoutHeader($headerName);
        $this->assertEquals([$headerValue], $request->getHeader($headerName));
        $this->assertEquals([], $newRequest->getHeader($headerName));
    }

    public function testGetMethod()
    {
        $method = "PUT";

        $request = $this->createRequest();
        $newRequest = $request->withMethod($method);
        $this->assertEquals("GET", $request->getMethod());
        $this->assertEquals($method, $newRequest->getMethod());
    }

    public function testGetQueryParams()
    {
        $queryParamName = "abc";
        $queryParamValue = "cde";
        $queryParams = [$queryParamName => $queryParamValue];

        $request = $this->createRequest();
        $newRequest = $request->withQueryParams($queryParams);
        $this->assertEquals([], $request->getQueryParams());
        $this->assertEquals($queryParams, $newRequest->getQueryParams());
    }

    public function testGetParsedBody()
    {
        $parsedBody = [
            "data" => [
                "type" => "cat",
                "id" => "tOm"
            ]
        ];

        $request = $this->createRequest();
        $newRequest = $request->withParsedBody($parsedBody);
        $this->assertEquals(null, $request->getParsedBody());
        $this->assertEquals($parsedBody, $newRequest->getParsedBody());
    }

    public function testGetAttributes()
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

    private function createRequest()
    {
        $psrRequest = new DiactorosRequest();
        return new Request($psrRequest);
    }

    private function createRequestWithJsonBody(array $body)
    {
        $psrRequest = new DiactorosRequest();
        $psrRequest = $psrRequest->withParsedBody($body);
        return new Request($psrRequest);
    }

    private function createRequestWithHeaders(array $headers)
    {
        $psrRequest = new DiactorosRequest([], [], null, null, "php://temp", $headers);
        return new Request($psrRequest);
    }

    private function createRequestWithHeader($headerName, $headerValue)
    {
        $psrRequest = new DiactorosRequest([], [], null, null, "php://temp", [$headerName => $headerValue]);
        return new Request($psrRequest);
    }

    private function createRequestWithQueryParams(array $queryParams)
    {
        $psrRequest = new DiactorosRequest();
        $psrRequest = $psrRequest->withQueryParams($queryParams);
        return new Request($psrRequest);
    }
}
