<?php
namespace WoohooLabs\Yin\Tests\JsonApi\Negotiation;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Exception\MediaTypeUnacceptable;
use WoohooLabs\Yin\JsonApi\Exception\MediaTypeUnsupported;
use WoohooLabs\Yin\JsonApi\Exception\QueryParamUnrecognized;
use WoohooLabs\Yin\JsonApi\Exception\RequestBodyInvalidJson;
use WoohooLabs\Yin\JsonApi\Negotiation\RequestValidator;
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
        $serverRequest = $this->getMockForAbstractClass(ServerRequestInterface::class);
        $exceptionFactory = $this->getMockForAbstractClass(ExceptionFactoryInterface::class);

        $request = $this->createRequestMock($serverRequest, $exceptionFactory);

        $request->expects($this->once())
            ->method("validateContentTypeHeader")
            ->will($this->returnValue(true));
        ;

        $request->expects($this->once())
            ->method("validateAcceptHeader")
            ->will($this->returnValue(true));
        ;

        $validator = $this->createRequestValidator($serverRequest);

        $validator->negotiate($request);
    }

    /**
     * @test
     * @dataProvider getInvalidContentTypes
     */
    public function negotiateThrowMediaTypeUnsupported($contentType)
    {
        // Content-Type is invalid, Accept is valid
        $serverRequest = $this->createServerRequest($contentType, "application/vnd.api+json");

        $request = $this->createRequest($serverRequest, $contentType);
        $validator = $this->createRequestValidator($serverRequest);

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
        $serverRequest = $this->createServerRequest("application/vnd.api+json", $accept);

        $request = $this->createRequest($serverRequest, "application/vnd.api+json");
        $validator = $this->createRequestValidator($serverRequest);

        $this->expectException(MediaTypeUnacceptable::class);
        $validator->negotiate($request);
    }

    /**
     * @test
     */
    public function validQueryParams()
    {
        $serverRequest = $this->createServerRequest("application/vnd.api+json");
        $serverRequest->expects($this->once())
            ->method("getQueryParams")
            ->will($this->returnValue([
                    "fields" => ["foo" => "bar"],
                    "include" => "baz",
                    "sort" => "asc",
                    "page" => "1",
                    "filter" => "search"
                ]
            ));

        $request = $this->createRequest($serverRequest, "application/vnd.api+json");
        $validator = $this->createRequestValidator($serverRequest);

        $response = $validator->validateQueryParams($request);

        $this->assertNull($response);
    }

    /**
     * @test
     */
    public function invalidQueryParamsThrowException()
    {
        $serverRequest = $this->createServerRequest("application/vnd.api+json");
        $serverRequest->expects($this->once())
            ->method("getQueryParams")
            ->will($this->returnValue(["foo" => "bar"]));

        $request = $this->createRequest($serverRequest, "application/vnd.api+json");
        $validator = $this->createRequestValidator($serverRequest);

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
        $serverRequest = $this->createServerRequest("application/vnd.api+json");
        $this->setFakeBody($serverRequest, $message);
        $request = $this->createRequest($serverRequest, "application/vnd.api+json");
        $validator = $this->createRequestValidator($serverRequest);

        $response = $validator->lintBody($request);

        $this->assertNull($response);
    }

    /**
     * @test
     * @dataProvider getValidJsonMessages
     */
    public function lintOnValidMessageReturnNull($message)
    {
        $serverRequest = $this->createServerRequest("application/vnd.api+json");
        $this->setFakeBody($serverRequest, $message);
        $request = $this->createRequest($serverRequest, "application/vnd.api+json");
        $validator = $this->createRequestValidator($serverRequest);

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


    private function createRequest($serverRequest, $contentType)
    {
        $exceptionInterface = new DefaultExceptionFactory($serverRequest);

        $request = new Request($serverRequest, $exceptionInterface);

        return $request;
    }

    private function setFakeBody(ServerRequestInterface $request, $body)
    {
        $stream = $this->getMockForAbstractClass(StreamInterface::class);

        $stream->expects($this->once())
            ->method("__toString")
            ->will($this->returnValue($body));

        /** @var \PHPUnit_Framework_MockObject_MockObject $request */
        $request->expects($this->once())
            ->method("getBody")
            ->will($this->returnValue($stream));
    }

    private function createRequestMock($serverRequest, ExceptionFactoryInterface $exceptionFactory)
    {
        return $this->getMockForAbstractClass(RequestInterface::class, [$serverRequest, $exceptionFactory]);
    }

    private function createRequestValidator($serverRequest, $includeOriginalMessageResponse = true)
    {
        $exceptionInterface = new DefaultExceptionFactory($serverRequest);
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
