<?php
namespace WoohooLabs\Yin\JsonApi\Negotiation;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Exception\MediaTypeUnacceptable;
use WoohooLabs\Yin\JsonApi\Exception\MediaTypeUnsupported;
use WoohooLabs\Yin\JsonApi\Exception\QueryParamUnrecognized;
use WoohooLabs\Yin\JsonApi\Exception\RequestBodyInvalidJson;
use WoohooLabs\Yin\JsonApi\Request\Request;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;

class RequestValidatorTest extends TestCase
{
    /**
     * Test valid request without Request validation Exceptions
     * @test
     */
    public function negotiateValidRequest()
    {
        $server = $this->getMockForAbstractClass(ServerRequestInterface::class);
        $exceptionFactory = $this->getMockForAbstractClass(ExceptionFactoryInterface::class);

        $request = $this->createRequestMock($server, $exceptionFactory);

        $request->expects($this->once())
            ->method("validateContentTypeHeader")
            ->will($this->returnValue(true));
        ;

        $request->expects($this->once())
            ->method("validateAcceptHeader")
            ->will($this->returnValue(true));
        ;

        $validator = $this->createRequestValidator($server);

        $validator->negotiate($request);
    }

    /**
     * @test
     * @dataProvider getInvalidContentTypes
     */
    public function negotiateThrowMediaTypeUnsupported($contentType)
    {
        // Content-Type is invalid, Accept is valid
        $server = $this->createServerRequest($contentType, "application/vnd.api+json");

        $request = $this->createRequest($server, $contentType);
        $validator = $this->createRequestValidator($server);

        $this->expectException(MediaTypeUnsupported::class);
        $validator->negotiate($request);
    }

    /**
     * @test
     * @dataProvider getInvalidContentTypes
     */
    public function negotiateThrowTypeUnacceptable($accept)
    {
        // Content-Type is valid, Accept is invalid
        $server = $this->createServerRequest("application/vnd.api+json", $accept);

        $request = $this->createRequest($server, "application/vnd.api+json");
        $validator = $this->createRequestValidator($server);

        $this->expectException(MediaTypeUnacceptable::class);
        $validator->negotiate($request);
    }

    /**
     * @test
     */
    public function validQueryParams()
    {
        $server = $this->createServerRequest("application/vnd.api+json");
        $server->expects($this->once())
            ->method("getQueryParams")
            ->will($this->returnValue([
                    "fields" => ["foo" => "bar"],
                    "include" => "baz",
                    "sort" => "asc",
                    "page" => "1",
                    "filter" => "search"
                ]
            ));

        $request = $this->createRequest($server, "application/vnd.api+json");
        $validator = $this->createRequestValidator($server);

        $response = $validator->validateQueryParams($request);

        $this->assertNull($response);
    }

    /**
     * @test
     */
    public function invalidQueryParamsThrowException()
    {
        $server = $this->createServerRequest("application/vnd.api+json");
        $server->expects($this->once())
            ->method("getQueryParams")
            ->will($this->returnValue(["foo" => "bar"]));

        $request = $this->createRequest($server, "application/vnd.api+json");
        $validator = $this->createRequestValidator($server);

        $this->expectException(QueryParamUnrecognized::class);
        $this->expectExceptionMessage("Query parameter 'foo' can't be recognized!");
        $validator->validateQueryParams($request);

    }

    /**
     * @test
     * @dataProvider getEmptyMessages
     */
    public function lintOnEmptyMessageReturnNull($message)
    {
        $server = $this->createServerRequest("application/vnd.api+json");
        $this->setFakeBody($server, $message);
        $request = $this->createRequest($server, "application/vnd.api+json");
        $validator = $this->createRequestValidator($server);

        $response = $validator->lintBody($request);

        $this->assertNull($response);
    }

    /**
     * @test
     * @dataProvider getValidJsonMessages
     */
    public function lintOnValidMessageReturnNull($message)
    {
        $server = $this->createServerRequest("application/vnd.api+json");
        $this->setFakeBody($server, $message);
        $request = $this->createRequest($server, "application/vnd.api+json");
        $validator = $this->createRequestValidator($server);

        $response = $validator->lintBody($request);

        $this->assertNull($response);
    }

    /**
     * @test
     * @dataProvider getInvalidJsonMessages
     */
    public function lintOnInvalidMessageThrowException($message)
    {
        $server = $this->createServerRequest("application/vnd.api+json");
        $this->setFakeBody($server, $message);
        $request = $this->createRequest($server, "application/vnd.api+json");
        $validator = $this->createRequestValidator($server);

        $this->expectException(RequestBodyInvalidJson::class);
        $validator->lintBody($request);
    }

    public function createServerRequest($contentType, $accept = "")
    {
        $server = $this->getMockForAbstractClass(ServerRequestInterface::class);

        $map = [
            ["Content-Type", $contentType],
            ["Accept", $accept]
        ];
        $server->expects($this->any())
            ->method("getHeaderLine")
            ->will($this->returnValueMap($map));


        return $server;
    }


    public function createRequest($server, $contentType)
    {
        $exceptionInterface = new DefaultExceptionFactory($server);

        $request = new Request($server, $exceptionInterface);

        return $request;
    }

    protected function setFakeBody($server, $body)
    {
        $server->expects($this->once())
            ->method("getBody")
            ->will($this->returnValue($body));
    }

    protected function createRequestMock($server, $exceptionFactory)
    {
        return $this->getMockForAbstractClass(RequestInterface::class, [$server, $exceptionFactory]);
    }

    private function createRequestValidator($server, $includeOriginalMessageResponse = true)
    {
        $exceptionInterface = new DefaultExceptionFactory($server);
        return new RequestValidator($exceptionInterface, $includeOriginalMessageResponse);
    }

    public function getInvalidContentTypes()
    {
        return [
            ["application/vnd.api+json; charset=utf-8"],
            ['application/vnd.api+json; ext="ext1,ext2"'],
        ];
    }

    public function getValidContentTypes()
    {
        return [
            ["application/vnd.api+json"],
            ["text/html; charset=utf-8"],
        ];
    }

    public function getEmptyMessages()
    {
        return [[''], [null], [0]];
    }

    public function getValidJsonMessages()
    {
        return [
            ['{}'],
            ['{"employees":[
                {"firstName":"John", "lastName":"Doe"},
                {"firstName":"Anna", "lastName":"Smith"},
                {"firstName":"Peter", "lastName":"Jones"}
            ]}'],
        ];
    }

    public function getInvalidJsonMessages()
    {
        return [
            ["{abc"],
            ["{\xEF\xBB\xBF}"],
        ];
    }
}
