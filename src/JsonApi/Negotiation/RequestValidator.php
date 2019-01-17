<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Negotiation;

use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Exception\JsonApiExceptionInterface;
use WoohooLabs\Yin\JsonApi\Exception\MediaTypeUnacceptable;
use WoohooLabs\Yin\JsonApi\Exception\MediaTypeUnsupported;
use WoohooLabs\Yin\JsonApi\Exception\QueryParamUnrecognized;
use WoohooLabs\Yin\JsonApi\Exception\RequestBodyInvalidJson;
use WoohooLabs\Yin\JsonApi\Request\JsonApiRequestInterface;

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
    public function negotiate(JsonApiRequestInterface $request): void
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
    public function validateQueryParams(JsonApiRequestInterface $request): void
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
    public function validateJsonBody(JsonApiRequestInterface $request): void
    {
        $this->lintBody($request);
    }

    /**
     * @throws RequestBodyInvalidJson|JsonApiExceptionInterface
     * @deprecated since 3.1.0, will be removed in 4.0.0. Use RequestValidator::validateJsonBody() instead.
     */
    public function lintBody(JsonApiRequestInterface $request): void
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
