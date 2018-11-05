<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Negotiation;

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

    protected function validateJsonMessage(string $message): string
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

    protected function validateJsonApiMessage(string $message): array
    {
        if (empty($message)) {
            return [];
        }

        $decodedMessage = json_decode($message);

        $validator = new Validator();
        $validator->validate(
            $decodedMessage,
            (object) ['$ref' => "file://" . realpath(__DIR__ . "/json-api-schema.json")]
        );

        return $validator->getErrors();
    }
}
