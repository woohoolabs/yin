<?php
namespace WoohooLabs\Yin\JsonApi\Negotiation;

use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Exception\MediaTypeUnacceptable;
use WoohooLabs\Yin\JsonApi\Exception\MediaTypeUnsupported;
use WoohooLabs\Yin\JsonApi\Exception\QueryParamUnrecognized;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;

class RequestValidator extends MessageValidator
{
    /**
     * @param \WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface $exceptionFactory
     * @param bool $includeOriginalMessageInResponse
     */
    public function __construct(ExceptionFactoryInterface $exceptionFactory, $includeOriginalMessageInResponse = true)
    {
        parent::__construct($exceptionFactory, $includeOriginalMessageInResponse);
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @throws \Exception
     */
    public function negotiate(RequestInterface $request)
    {
        try {
            $request->validateContentTypeHeader();
            $request->validateAcceptHeader();
        } catch (MediaTypeUnacceptable $e) {
            throw $this->exceptionFactory->createMediaTypeUnacceptableException($request, $e->getMediaTypeName());
        } catch (MediaTypeUnsupported $e) {
            throw $this->exceptionFactory->createMediaTypeUnsupportedException($request, $e->getMediaTypeName());
        }
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @throws \Exception
     */
    public function validateQueryParams(RequestInterface $request)
    {
        try {
            $request->validateQueryParams();
        } catch (QueryParamUnrecognized $e) {
            throw $this->exceptionFactory->createQueryParamUnrecognizedException(
                $request,
                $e->getUnrecognizedQueryParam()
            );
        }
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @throws \Exception
     */
    public function lintBody(RequestInterface $request)
    {
        $errorMessage = $this->lintMessage($request->getBody()->getContents());

        if ($errorMessage) {
            throw $this->exceptionFactory->createRequestBodyInvalidJsonException(
                $request,
                $errorMessage,
                $this->includeOriginalMessage
            );
        }
    }
}
