<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Exception;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Document\AbstractErrorDocument;
use WoohooLabs\Yin\JsonApi\Document\ErrorDocument;
use WoohooLabs\Yin\JsonApi\Schema\Error;

class ResponseBodyInvalidJson extends JsonApiException
{
    /**
     * @var ResponseInterface
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

    public function __construct(ResponseInterface $response, string $lintMessage, bool $includeOriginalBody)
    {
        parent::__construct("Response body is an invalid JSON document: '$lintMessage'!");
        $this->response = $response;
        $this->lintMessage = $lintMessage;
        $this->includeOriginalBody = $includeOriginalBody;
    }

    protected function createErrorDocument(): AbstractErrorDocument
    {
        $errorDocument = new ErrorDocument();

        if ($this->includeOriginalBody) {
            $errorDocument->setMeta(["original" => $this->response->getBody()->__toString()]);
        }

        return $errorDocument;
    }

    protected function getErrors(): array
    {
        return [
            Error::create()
                ->setStatus("500")
                ->setCode("RESPONSE_BODY_INVALID_JSON")
                ->setTitle("Response body is an invalid JSON document")
                ->setDetail($this->getMessage())
        ];
    }

    public function getLintMessage(): string
    {
        return $this->lintMessage;
    }
}
