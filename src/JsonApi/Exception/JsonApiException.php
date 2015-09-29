<?php
namespace WoohooLabs\Yin\JsonApi\Exception;

use WoohooLabs\Yin\JsonApi\Schema\Error;

abstract class JsonApiException extends \Exception implements JsonApiExceptionInterface
{
    /**
     * @var \WoohooLabs\Yin\JsonApi\Transformer\AbstractErrorDocument
     */
    protected $errorDocument;

    /**
     * @param string $message
     */
    public function __construct($message = "")
    {
        parent::__construct($message);
    }

    /**
     * @return \WoohooLabs\Yin\JsonApi\Transformer\AbstractErrorDocument
     */
    protected function createErrorDocument()
    {
        return new Error();
    }

    /**
     * @return \WoohooLabs\Yin\JsonApi\Schema\Error[]
     */
    abstract protected function getErrors();

    /**
     * @return \WoohooLabs\Yin\JsonApi\Transformer\AbstractErrorDocument
     */
    public function getErrorDocument()
    {
        $document = $this->createErrorDocument();
        foreach ($this->getErrors() as $error) {
            $document->addError($error);
        }

        return $document;
    }
}
