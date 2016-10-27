<?php
namespace WoohooLabs\Yin\JsonApi\Negotiation;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Serializer\DeserializerInterface;

class ResponseValidator extends AbstractMessageValidator
{
    /**
     * @var DeserializerInterface
     */
    private $deserializer;

    /**
     * @param bool $includeOriginalMessageInResponse
     */
    public function __construct(
        DeserializerInterface $deserializer,
        ExceptionFactoryInterface $exceptionFactory,
        $includeOriginalMessageInResponse = true
    ) {
        parent::__construct($exceptionFactory, $includeOriginalMessageInResponse);
        $this->deserializer = $deserializer;
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @throws \Exception
     */
    public function lintBody(ResponseInterface $response)
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
     * @param \Psr\Http\Message\ResponseInterface $response
     * @throws \Exception
     */
    public function validateBody(ResponseInterface $response)
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
