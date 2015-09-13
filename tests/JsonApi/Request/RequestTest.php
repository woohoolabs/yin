<?php
namespace WoohooLabsTest\Yin\JsonApi\Request;

use PHPUnit_Framework_TestCase;
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
        $this->assertValidContentTypeHeader('application/vnd.api+json; ext="ext1,ext2"; supported-ext="ext1,ext2,ext3"');
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

    public function testValidateJsonApiAcceptHeaderWithAdditionalMediaTypes()
    {
        $this->assertValidAcceptHeader('application/vnd.api+json; ext="ext1,ext2"; charset=utf-8; lang=en');
    }

    private function assertValidAcceptHeader($value)
    {
        try {
            $this->createRequestWithHeader("Accept", $value)->validateContentTypeHeader();
        } catch (\Exception $e) {
            $this->fail("No exception should have been thrown, but the following was catched: " . $e->getMessage());
        }
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
        $this->assertTrue(
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
        $this->assertTrue(
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
        $defaultRelationships = ["contacts"];
        $queryParams = ["include" => ""];

        $request = $this->createRequestWithQueryParams($queryParams);
        $this->assertTrue(
            $request->isIncludedRelationship($baseRelationshipPath, $requiredRelationship, $defaultRelationships)
        );
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
