<?php
namespace WoohooLabs\Yin\JsonApi\Negotiation;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;

class ResponseValidator extends MessageValidator
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
     * @param \Psr\Http\Message\ResponseInterface $response
     * @throws \Exception
     */
    public function lintBody(ResponseInterface $response)
    {
        $errorMessage = $this->lintMessage($response->getBody());

        if (empty($errorMessage) === false) {
            $this->exceptionFactory->createResponseBodyInvalidJsonException(
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
        $errors = $this->validateMessage(json_decode($response->getBody()));

        if (empty($errors) === false) {
            $this->exceptionFactory->createResponseBodyInvalidJsonApiException(
                $response,
                $errors,
                $this->includeOriginalMessage
            );
        }
    }
}
