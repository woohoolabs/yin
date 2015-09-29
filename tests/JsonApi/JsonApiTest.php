<?php
namespace WoohooLabsTest\Yin\JsonApi\Transformer;

use PHPUnit_Framework_TestCase;
use Psr\Http\Message\ServerRequestInterface;
use WoohooLabs\Yin\JsonApi\JsonApi;
use WoohooLabs\Yin\JsonApi\Request\Request;
use WoohooLabs\Yin\JsonApi\Response\CreateResponse;
use WoohooLabs\Yin\JsonApi\Response\DeleteResponse;
use WoohooLabs\Yin\JsonApi\Response\FetchResponse;
use WoohooLabs\Yin\JsonApi\Response\FetchRelationshipResponse;
use WoohooLabs\Yin\JsonApi\Response\UpdateRelationshipResponse;
use WoohooLabs\Yin\JsonApi\Response\UpdateResponse;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;

class JsonApiTest extends PHPUnit_Framework_TestCase
{
    public function testGetRequest()
    {
        $request = $this->createRequest();
        $request = $request->withMethod("PUT");

        $jsonApi = $this->createJsonApi($request);
        $this->assertEquals($request, $jsonApi->getRequest());
    }

    public function testDisableIncludesWhenMissing()
    {
        $request = $this->createRequest();

        try {
            $this->createJsonApi($request)->disableIncludes();
        } catch (\Exception $e) {
            $this->fail();
        }
    }

    /**
     * @expectedException \WoohooLabs\Yin\JsonApi\Exception\InclusionUnsupported
     */
    public function testDisableIncludesWhenEmpty()
    {
        $request = $this->createRequest();
        $request = $request->withQueryParams(["include" => ""]);

        $this->createJsonApi($request)->disableIncludes();
    }

    /**
     * @expectedException \WoohooLabs\Yin\JsonApi\Exception\InclusionUnsupported
     */
    public function testDisableIncludesWhenSet()
    {
        $request = $this->createRequest();
        $request = $request->withQueryParams(["include" => "users"]);

        $this->createJsonApi($request)->disableIncludes();
    }

    public function testDisableSortingWhenMissing()
    {
        $request = $this->createRequest();

        try {
            $this->createJsonApi($request)->disableSorting();
        } catch (\Exception $e) {
            $this->fail();
        }
    }

    /**
     * @expectedException \WoohooLabs\Yin\JsonApi\Exception\SortingUnsupported
     */
    public function testDisableSortingWhenEmpty()
    {
        $request = $this->createRequest();
        $request = $request->withQueryParams(["sort" => ""]);

        $this->createJsonApi($request)->disableSorting();
    }

    /**
     * @expectedException \WoohooLabs\Yin\JsonApi\Exception\SortingUnsupported
     */
    public function testDisableSortingWhenSet()
    {
        $request = $this->createRequest();
        $request = $request->withQueryParams(["sort" => "firstname"]);

        $this->createJsonApi($request)->disableSorting();
    }

    public function testCreateResponse()
    {
        $response = $this->createJsonApi()->createResponse();
        $this->assertInstanceOf(CreateResponse::class, $response);
    }

    public function testDeleteResponse()
    {
        $response = $this->createJsonApi()->deleteResponse();
        $this->assertInstanceOf(DeleteResponse::class, $response);
    }

    public function testFetchResponse()
    {
        $response = $this->createJsonApi()->fetchResponse();
        $this->assertInstanceOf(FetchResponse::class, $response);
    }

    public function testFetchRelationshipResponse()
    {
        $response = $this->createJsonApi()->fetchRelationshipResponse("");
        $this->assertInstanceOf(FetchRelationshipResponse::class, $response);
    }

    public function testUpdateResponse()
    {
        $response = $this->createJsonApi()->updateResponse();
        $this->assertInstanceOf(UpdateResponse::class, $response);
    }

    public function testUpdateRelationshipResponse()
    {
        $response = $this->createJsonApi()->updateRelationshipResponse("");
        $this->assertInstanceOf(UpdateRelationshipResponse::class, $response);
    }

    private function createJsonApi(Request $request = null, Response $response = null)
    {
        return new JsonApi($request ? $request : $this->createRequest(), $response ? $response : new Response());
    }

    private function createRequest(ServerRequestInterface $request = null)
    {
        return new Request($request ? $request : new ServerRequest());
    }
}
