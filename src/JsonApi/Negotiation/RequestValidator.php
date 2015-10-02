<?php
namespace WoohooLabs\Yin\JsonApi\Negotiation;

use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
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
        $request->validateContentTypeHeader();
        $request->validateAcceptHeader();
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @throws \Exception
     */
    public function validateQueryParams(RequestInterface $request)
    {
        $request->validateQueryParams();
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @throws \Exception
     */
    public function lintBody(RequestInterface $request)
    {
        $errorMessage = $this->lintMessage($request->getBody());

        if (empty($errorMessage) === false) {
            $this->exceptionFactory->createRequestBodyInvalidJsonException(
                $request,
                $errorMessage,
                $this->includeOriginalMessage
            );
        }
    }
}
