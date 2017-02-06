<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Negotiation;

use Exception;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Exception\MediaTypeUnacceptable;
use WoohooLabs\Yin\JsonApi\Exception\MediaTypeUnsupported;
use WoohooLabs\Yin\JsonApi\Exception\QueryParamUnrecognized;
use WoohooLabs\Yin\JsonApi\Exception\RequestBodyInvalidJson;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;

class RequestValidator extends AbstractMessageValidator
{
    public function __construct(
        ExceptionFactoryInterface $exceptionFactory,
        bool $includeOriginalMessageInResponse = true
    ) {
        parent::__construct($exceptionFactory, $includeOriginalMessageInResponse);
    }

    /**
     * @return void
     * @throws MediaTypeUnsupported|MediaTypeUnacceptable|Exception
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
     * @return void
     * @throws QueryParamUnrecognized|Exception
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
     * @return void
     * @throws RequestBodyInvalidJson|Exception
     */
    public function lintBody(RequestInterface $request)
    {
        $errorMessage = $this->lintMessage($request->getBody()->__toString());

        if ($errorMessage) {
            throw $this->exceptionFactory->createRequestBodyInvalidJsonException(
                $request,
                $errorMessage,
                $this->includeOriginalMessage
            );
        }
    }
}
