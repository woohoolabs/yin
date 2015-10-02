<?php
namespace WoohooLabs\Yin\JsonApi\Exception;

use WoohooLabs\Yin\JsonApi\Document\ErrorDocument;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Schema\Error;

class RequestBodyInvalidJson extends JsonApiException
{
    /**
     * @var \WoohooLabs\Yin\JsonApi\Request\RequestInterface
     */
    protected $request;

    /**
     * @var string
     */
    protected $lintMessage;

    /**
     * @var bool
     */
    protected $includeOriginalBody;

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param string $lintMessage
     * @param boolean $includeOriginalBody
     */
    public function __construct(RequestInterface $request, $lintMessage, $includeOriginalBody)
    {
        parent::__construct("Request body is an invalid JSON document: '$lintMessage'!");
        $this->request = $request;
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
            $errorDocument->setMeta(["original" => json_decode($this->request->getBody(), true)]);
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
                ->setStatus(400)
                ->setCode("REQUEST_BODY_INVALID_JSON")
                ->setTitle("Request body is an invalid JSON document")
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
