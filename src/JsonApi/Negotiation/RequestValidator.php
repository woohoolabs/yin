<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Negotiation;

use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Exception\JsonApiExceptionInterface;
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
     * @throws MediaTypeUnsupported|MediaTypeUnacceptable|JsonApiExceptionInterface
     */
    public function negotiate(RequestInterface $request): void
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
     * @throws QueryParamUnrecognized|JsonApiExceptionInterface
     */
    public function validateQueryParams(RequestInterface $request): void
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
     * @throws RequestBodyInvalidJson|JsonApiExceptionInterface
     */
    public function lintBody(RequestInterface $request): void
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
