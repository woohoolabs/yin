<?php
namespace WoohooLabsTest\Yin\JsonApi\Transformer;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Exception\InclusionUnsupported;
use WoohooLabs\Yin\JsonApi\Exception\SortingUnsupported;
use WoohooLabs\Yin\JsonApi\JsonApi;
use WoohooLabs\Yin\JsonApi\Request\Request;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;

class JsonApiTest extends TestCase
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
     */
    public function disableIncludesWhenEmpty()
    {
        $request = $this->createRequest();
        $request = $request->withQueryParams(["include" => ""]);

        $this->expectException(InclusionUnsupported::class);
        $this->createJsonApi($request)->disableIncludes();
    }

    /**
     * @test
     */
    public function disableIncludesWhenSet()
    {
        $request = $this->createRequest();
        $request = $request->withQueryParams(["include" => "users"]);

        $this->expectException(InclusionUnsupported::class);
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
     */
    public function disableSortingWhenEmpty()
    {
        $request = $this->createRequest();
        $request = $request->withQueryParams(["sort" => ""]);

        $this->expectException(SortingUnsupported::class);
        $this->createJsonApi($request)->disableSorting();
    }

    /**
     * @test
     */
    public function disableSortingWhenSet()
    {
        $request = $this->createRequest();
        $request = $request->withQueryParams(["sort" => "firstname"]);

        $this->expectException(SortingUnsupported::class);
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
            $exceptionFactory ? $exceptionFactory : new DefaultExceptionFactory()
        );
    }

    private function createRequest(ServerRequestInterface $request = null)
    {
        return new Request($request ? $request : new ServerRequest(), new DefaultExceptionFactory());
    }
}
