<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Exception;

use WoohooLabs\Yin\JsonApi\Document\AbstractErrorDocument;
use WoohooLabs\Yin\JsonApi\Document\ErrorDocument;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Schema\Error;

class RequestBodyInvalidJson extends JsonApiException
{
    /**
     * @var RequestInterface
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

    public function __construct(RequestInterface $request, string $lintMessage, bool $includeOriginalBody)
    {
        parent::__construct("Request body is an invalid JSON document: '$lintMessage'!");
        $this->request = $request;
        $this->lintMessage = $lintMessage;
        $this->includeOriginalBody = $includeOriginalBody;
    }

    protected function createErrorDocument(): AbstractErrorDocument
    {
        $errorDocument = new ErrorDocument();

        if ($this->includeOriginalBody) {
            $errorDocument->setMeta(["original" => $this->request->getBody()->__toString()]);
        }

        return $errorDocument;
    }

    protected function getErrors(): array
    {
        return [
            Error::create()
                ->setStatus("400")
                ->setCode("REQUEST_BODY_INVALID_JSON")
                ->setTitle("Request body is an invalid JSON document")
                ->setDetail($this->getMessage())
        ];
    }

    public function getLintMessage(): string
    {
        return $this->lintMessage;
    }
}
