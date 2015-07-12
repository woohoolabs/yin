<?php
namespace WoohooLabs\Yin\JsonApi\Transformer;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Schema\Error;

abstract class AbstractErrorDocument extends AbstractDocument
{
    /**
     * @var array
     */
    protected $errors = [];

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param int $statusCode
     */
    public function __construct(ResponseInterface $response, $statusCode)
    {
        parent::__construct($response, $statusCode);
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Schema\Error $error
     */
    public function addError(Error $error)
    {
        $this->errors[] = $error;
    }

    /**
     * @return array
     */
    protected function transformContent()
    {
        $content = parent::transformContent();

        // ERRORS
        $this->addOptionalSimpleTransformedCollectionToArray($content, "errors", $this->errors);

        return $content;
    }
}
