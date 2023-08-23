<?php

declare(strict_types=1);

namespace Devleand\Yin\JsonApi\Negotiation;

use Devleand\Yin\JsonApi\Exception\JsonApiExceptionInterface;
use Devleand\Yin\JsonApi\Exception\MediaTypeUnacceptable;
use Devleand\Yin\JsonApi\Exception\MediaTypeUnsupported;
use Devleand\Yin\JsonApi\Exception\QueryParamUnrecognized;
use Devleand\Yin\JsonApi\Exception\RequestBodyInvalidJson;
use Devleand\Yin\JsonApi\Request\JsonApiRequestInterface;

class RequestValidator extends AbstractMessageValidator
{
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
        $body = $request->getBody();
        $errorMessage = $this->validateJsonMessage($body->__toString());

        if ($body->isSeekable()) {
            $body->rewind();
        }

        if ($errorMessage !== "") {
            throw $this->exceptionFactory->createRequestBodyInvalidJsonException(
                $request,
                $errorMessage,
                $this->includeOriginalMessage
            );
        }
    }

    /**
     * @throws JsonApiExceptionInterface
     */
    public function validateTopLevelMembers(JsonApiRequestInterface $request): void
    {
        $request->validateTopLevelMembers();
    }
}
