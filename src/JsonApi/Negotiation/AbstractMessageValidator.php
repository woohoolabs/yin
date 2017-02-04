<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Negotiation;

use JsonSchema\RefResolver;
use JsonSchema\Uri\UriResolver;
use JsonSchema\Uri\UriRetriever;
use JsonSchema\Validator;
use Seld\JsonLint\JsonParser;
use Seld\JsonLint\ParsingException;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;

abstract class AbstractMessageValidator
{
    /**
     * @var \WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface
     */
    protected $exceptionFactory;

    /**
     * @var bool
     */
    protected $includeOriginalMessage;

    /**
     * @param \WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface $exceptionFactory
     * @param bool $includeOriginalMessageInResponse
     */
    public function __construct(ExceptionFactoryInterface $exceptionFactory, $includeOriginalMessageInResponse = true)
    {
        $this->exceptionFactory = $exceptionFactory;
        $this->includeOriginalMessage = $includeOriginalMessageInResponse;
    }

    /**
     * @param string $message
     * @return string
     */
    protected function lintMessage($message)
    {
        if (empty($message)) {
            return "";
        }

        $linter = new JsonParser();
        $result = $linter->lint($message);

        if ($result instanceof ParsingException) {
            return $result->getMessage();
        }

        return "";
    }

    /**
     * @param string $message
     * @return array
     */
    protected function validateMessage($message)
    {
        if (empty($message) === true) {
            return [];
        }

        $refResolver = new RefResolver(new UriRetriever(), new UriResolver());
        $schema = $refResolver->resolve('file://' . realpath(__DIR__ . "/json-api-schema.json"));

        $validator = new Validator();
        $validator->check(json_decode($message), $schema);

        return $validator->getErrors();
    }
}
