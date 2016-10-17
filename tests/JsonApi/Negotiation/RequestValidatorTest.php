<?php
namespace WoohooLabs\Yin\JsonApi\Negotiation;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Request\Request;
use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;

class RequestValidatorTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test valid request without Request validation Exceptions
     * @test
     */
    public function negotiateValidRequest()
    {
        $server = $this->getMockForAbstractClass('\Psr\Http\Message\ServerRequestInterface');
        $exceptionFactory = $this->getMockForAbstractClass('\WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface');

        $request = $this->createRequestMock($server, $exceptionFactory);

        $request->expects($this->once())
            ->method('validateContentTypeHeader')
            ->will($this->returnValue(true));
        ;

        $request->expects($this->once())
            ->method('validateAcceptHeader')
            ->will($this->returnValue(true));
        ;

        $validator = $this->createRequestValidator($server);

        $validator->negotiate($request);
    }


    /**
     * @test
     * @dataProvider getInvalidContentTypes
     * @expectedException \WoohooLabs\Yin\JsonApi\Exception\MediaTypeUnsupported
     */
    public function negotiateTrowMediaTypeUnsupported($contentType)
    {
        // Content type is invalid Accept is valid
        $server = $this->createServerRequest($contentType, 'application/vnd.api+json');

        $request = $this->createRequest($server, $contentType);
        $validator = $this->createRequestValidator($server);

        $validator->negotiate($request);
    }

    /**
     * @test
     * @dataProvider getInvalidContentTypes
     * @expectedException \WoohooLabs\Yin\JsonApi\Exception\MediaTypeUnacceptable
     */
    public function negotiateThrowTypeUnacceptable($accept)
    {
        // Content Type is valid, Accept is invalid
        $server = $this->createServerRequest('application/vnd.api+json', $accept);

        $request = $this->createRequest($server, 'application/vnd.api+json');
        $validator = $this->createRequestValidator($server);

        $validator->negotiate($request);
    }

    public function createServerRequest($contentType, $accept = '')
    {
        $server = $this->getMockForAbstractClass('\Psr\Http\Message\ServerRequestInterface');

        $map = array(
            array('Content-Type', $contentType),
            array('Accept', $accept)
        );
        $server->expects($this->any())
            ->method('getHeaderLine')
            ->will($this->returnValueMap($map));


        return $server;
    }


    public function createRequest($server, $contentType)
    {
        $exceptionInterface = new DefaultExceptionFactory($server);

        $request = new Request($server, $exceptionInterface);

        return $request;
    }

    protected function createRequestMock($server, $exceptionFactory)
    {
        return $this->getMockForAbstractClass('\WoohooLabs\Yin\JsonApi\Request\RequestInterface', [$server, $exceptionFactory]);
    }


    private function createRequestValidator($server, $includeOriginalMessageResponse = true)
    {
        $exceptionInterface = new DefaultExceptionFactory($server);
        return new RequestValidator($exceptionInterface, $includeOriginalMessageResponse);
    }

    public function getInvalidContentTypes()
    {
        return [
          ['application/zip'],
          ['application/octet-stream'],
          ['application/ms-word'],
          ['application/json'],
          ['application/x-javascript'],
        ];
    }

    public function getValidContentTypes()
    {
        return [
            ['application/vnd.api+json']
        ];
    }
}
