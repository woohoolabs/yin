<?php
namespace WoohooLabs\Yin\JsonApi\Exception;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Document\ErrorDocument;
use WoohooLabs\Yin\JsonApi\Schema\Error;

class ResponseBodyInvalidJson extends JsonApiException
{
    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;

    /**
     * @var string
     */
    protected $lintMessage;

    /**
     * @var bool
     */
    protected $includeOriginalBody;

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param string $lintMessage
     * @param boolean $includeOriginalBody
     */
    public function __construct(ResponseInterface $response, $lintMessage, $includeOriginalBody)
    {
        parent::__construct("Request body is an invalid JSON document: '$lintMessage'!");
        $this->response = $response;
        $this->lintMessage = $lintMessage;
        $this->includeOriginalBody = $includeOriginalBody;
    }

    /**
     * @return \WoohooLabs\Yin\JsonApi\Document\AbstractErrorDocument
     */
    protected function createErrorDocument()
    {
        $errorDocument = new ErrorDocument();

        if ($this->includeOriginalBody === true) {
            $errorDocument->setMeta(["original" => json_decode($this->response->getBody(), true)]);
        }

        return $errorDocument;
    }

    /**
     * @inheritDoc
     */
    protected function getErrors()
    {
        return [
            Error::create()
                ->setStatus(500)
                ->setCode("RESPONSE_BODY_INVALID_JSON")
                ->setTitle("Response body is an invalid JSON document")
                ->setDetail($this->getMessage())
        ];
    }

    /**
     * @return string
     */
    public function getLintMessage()
    {
        return $this->lintMessage;
    }
}
