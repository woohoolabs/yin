<?php
namespace WoohooLabs\Yin\JsonApi\Negotiation;

use JsonSchema\RefResolver;
use JsonSchema\Uri\UriRetriever;
use JsonSchema\Validator;
use Seld\JsonLint\JsonParser;
use Seld\JsonLint\ParsingException;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;

abstract class MessageValidator
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
    public function lintMessage($message)
    {
        if (empty($message) === true) {
            return null;
        }

        try {
            $linter = new JsonParser();
            $linter->lint($message);
        } catch (ParsingException $e) {
            return $e->getMessage();
        }

        return "";
    }

    /**
     * @param object $message
     * @return array
     */
    protected function validateMessage($message)
    {
        if (empty($message) === true) {
            return [];
        }

        $jsonApiSchemaPath = realpath(__DIR__ . "/json-api-schema.json");

        $retriever = new UriRetriever();
        $schema = $retriever->retrieve('file://' . $jsonApiSchemaPath);

        RefResolver::$maxDepth = 100;
        $refResolver = new RefResolver($retriever);
        $refResolver->resolve($schema, 'file://' . dirname($jsonApiSchemaPath) . "/json-api-schema.json");

        $validator = new Validator();
        $validator->check($message, $schema);

        return $validator->getErrors();
    }
}
