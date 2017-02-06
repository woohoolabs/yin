<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Negotiation;

use Exception;
use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
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
     * @return void
     * @throws ResponseBodyInvalidJson|Exception
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
     * @return void
     * @throws ResponseBodyInvalidJsonApi|Exception
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
