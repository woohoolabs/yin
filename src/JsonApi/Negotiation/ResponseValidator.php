<?php

declare(strict_types=1);

namespace Devleand\Yin\JsonApi\Negotiation;

use Psr\Http\Message\ResponseInterface;
use Devleand\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use Devleand\Yin\JsonApi\Exception\JsonApiExceptionInterface;
use Devleand\Yin\JsonApi\Exception\ResponseBodyInvalidJson;
use Devleand\Yin\JsonApi\Exception\ResponseBodyInvalidJsonApi;
use Devleand\Yin\JsonApi\Serializer\SerializerInterface;

class ResponseValidator extends AbstractMessageValidator
{
    /**
     * @var SerializerInterface
     */
    private $deserializer;

    public function __construct(
        SerializerInterface $deserializer,
        ExceptionFactoryInterface $exceptionFactory,
        bool $includeOriginalMessageInResponse = true,
        ?string $customSchemaPath = null
    ) {
        parent::__construct($exceptionFactory, $includeOriginalMessageInResponse, $customSchemaPath);
        $this->deserializer = $deserializer;
    }

    /**
     * @throws ResponseBodyInvalidJson|JsonApiExceptionInterface
     */
    public function validateJsonBody(ResponseInterface $response): void
    {
        $errorMessage = $this->validateJsonMessage($this->deserializer->getBodyAsString($response));

        if (empty($errorMessage) === false) {
            throw $this->exceptionFactory->createResponseBodyInvalidJsonException(
                $response,
                $errorMessage,
                $this->includeOriginalMessage
            );
        }
    }

    /**
     * @throws ResponseBodyInvalidJsonApi|JsonApiExceptionInterface
     */
    public function validateJsonApiBody(ResponseInterface $response): void
    {
        $errors = $this->validateJsonApiMessage($this->deserializer->getBodyAsString($response));

        if (empty($errors) === false) {
            throw $this->exceptionFactory->createResponseBodyInvalidJsonApiException(
                $response,
                $errors,
                $this->includeOriginalMessage
            );
        }
    }
}
