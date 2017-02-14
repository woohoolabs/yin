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
     * @var ExceptionFactoryInterface
     */
    protected $exceptionFactory;

    /**
     * @var bool
     */
    protected $includeOriginalMessage;

    public function __construct(
        ExceptionFactoryInterface $exceptionFactory,
        bool $includeOriginalMessageInResponse = true
    ) {
        $this->exceptionFactory = $exceptionFactory;
        $this->includeOriginalMessage = $includeOriginalMessageInResponse;
    }

    protected function lintMessage(string $message): string
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

    protected function validateMessage(string $message): array
    {
        if (empty($message)) {
            return [];
        }

        $refResolver = new RefResolver(new UriRetriever(), new UriResolver());
        $schema = $refResolver->resolve('file://' . realpath(__DIR__ . "/json-api-schema.json"));

        $validator = new Validator();
        $validator->check(json_decode($message), $schema);

        return $validator->getErrors();
    }
}
