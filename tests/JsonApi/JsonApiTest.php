<?php
namespace WoohooLabsTest\Yin\JsonApi\Transformer;

use PHPUnit_Framework_TestCase;
use Psr\Http\Message\ServerRequestInterface;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactory;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\JsonApi;
use WoohooLabs\Yin\JsonApi\Request\Request;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;

class JsonApiTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function getRequest()
    {
        $request = $this->createRequest();
        $request = $request->withMethod("PUT");

        $jsonApi = $this->createJsonApi($request);
        $this->assertEquals($request, $jsonApi->getRequest());
    }

    /**
     * @test
     */
    public function disableIncludesWhenMissing()
    {
        $request = $this->createRequest();

        try {
            $this->createJsonApi($request)->disableIncludes();
        } catch (\Exception $e) {
            $this->fail();
        }
    }

    /**
     * @test
     * @expectedException \WoohooLabs\Yin\JsonApi\Exception\InclusionUnsupported
     */
    public function disableIncludesWhenEmpty()
    {
        $request = $this->createRequest();
        $request = $request->withQueryParams(["include" => ""]);

        $this->createJsonApi($request)->disableIncludes();
    }

    /**
     * @test
     * @expectedException \WoohooLabs\Yin\JsonApi\Exception\InclusionUnsupported
     */
    public function disableIncludesWhenSet()
    {
        $request = $this->createRequest();
        $request = $request->withQueryParams(["include" => "users"]);

        $this->createJsonApi($request)->disableIncludes();
    }

    /**
     * @test
     */
    public function disableSortingWhenMissing()
    {
        $request = $this->createRequest();

        try {
            $this->createJsonApi($request)->disableSorting();
        } catch (\Exception $e) {
            $this->fail();
        }
    }

    /**
     * @test
     * @expectedException \WoohooLabs\Yin\JsonApi\Exception\SortingUnsupported
     */
    public function disableSortingWhenEmpty()
    {
        $request = $this->createRequest();
        $request = $request->withQueryParams(["sort" => ""]);

        $this->createJsonApi($request)->disableSorting();
    }

    /**
     * @test
     * @expectedException \WoohooLabs\Yin\JsonApi\Exception\SortingUnsupported
     */
    public function disableSortingWhenSet()
    {
        $request = $this->createRequest();
        $request = $request->withQueryParams(["sort" => "firstname"]);

        $this->createJsonApi($request)->disableSorting();
    }

    private function createJsonApi(
        Request $request = null,
        Response $response = null,
        ExceptionFactoryInterface $exceptionFactory = null
    ) {
        return new JsonApi(
            $request ? $request : $this->createRequest(),
            $response ? $response : new Response(),
            $exceptionFactory ? $exceptionFactory : new ExceptionFactory()
        );
    }

    private function createRequest(ServerRequestInterface $request = null)
    {
        return new Request($request ? $request : new ServerRequest());
    }
}
