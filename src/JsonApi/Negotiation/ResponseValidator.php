<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Negotiation;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Exception\JsonApiExceptionInterface;
use WoohooLabs\Yin\JsonApi\Exception\ResponseBodyInvalidJson;
use WoohooLabs\Yin\JsonApi\Exception\ResponseBodyInvalidJsonApi;
use WoohooLabs\Yin\JsonApi\Serializer\SerializerInterface;

class ResponseValidator extends AbstractMessageValidator
{
    /**
     * @var SerializerInterface
     */
    private $deserializer;

    public function __construct(
        SerializerInterface $deserializer,
        ExceptionFactoryInterface $exceptionFactory,
        bool $includeOriginalMessageInResponse = true
    ) {
        parent::__construct($exceptionFactory, $includeOriginalMessageInResponse);
        $this->deserializer = $deserializer;
    }

    /**
     * @throws ResponseBodyInvalidJson|JsonApiExceptionInterface
     */
    public function lintBody(ResponseInterface $response): void
    {
        $errorMessage = $this->lintMessage($this->deserializer->getBodyAsString($response));

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
    public function validateBody(ResponseInterface $response): void
    {
        $errors = $this->validateMessage($this->deserializer->getBodyAsString($response));

        if (empty($errors) === false) {
            throw $this->exceptionFactory->createResponseBodyInvalidJsonApiException(
                $response,
                $errors,
                $this->includeOriginalMessage
            );
        }
    }
}
