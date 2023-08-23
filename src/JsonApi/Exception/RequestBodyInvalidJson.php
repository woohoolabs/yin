<?php

declare(strict_types=1);

namespace Devleand\Yin\JsonApi\Exception;

use Devleand\Yin\JsonApi\Request\JsonApiRequestInterface;
use Devleand\Yin\JsonApi\Schema\Document\ErrorDocument;
use Devleand\Yin\JsonApi\Schema\Document\ErrorDocumentInterface;
use Devleand\Yin\JsonApi\Schema\Error\Error;

class RequestBodyInvalidJson extends AbstractJsonApiException
{
    /**
     * @var JsonApiRequestInterface
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

    public function __construct(JsonApiRequestInterface $request, string $lintMessage, bool $includeOriginalBody)
    {
        parent::__construct("Request body is an invalid JSON document: '$lintMessage'!", 400);
        $this->request = $request;
        $this->lintMessage = $lintMessage;
        $this->includeOriginalBody = $includeOriginalBody;
    }

    public function getErrorDocument(): ErrorDocumentInterface
    {
        $errorDocument = new ErrorDocument($this->getErrors());

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
                ->setDetail($this->getMessage()),
        ];
    }

    public function getLintMessage(): string
    {
        return $this->lintMessage;
    }
}
