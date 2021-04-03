<?php

declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Exception;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Schema\Document\ErrorDocument;
use WoohooLabs\Yin\JsonApi\Schema\Document\ErrorDocumentInterface;
use WoohooLabs\Yin\JsonApi\Schema\Error\Error;
use WoohooLabs\Yin\JsonApi\Schema\Error\ErrorSource;

use function json_decode;
use function print_r;
use function ucfirst;

class ResponseBodyInvalidJsonApi extends AbstractJsonApiException
{
    protected ResponseInterface $response;
    protected array $validationErrors;
    protected bool $includeOriginalBody;

    public function __construct(ResponseInterface $response, array $validationErrors, bool $includeOriginalBody)
    {
        parent::__construct("Response body is an invalid JSON:API document: " . print_r($validationErrors, true), 500);
        $this->response = $response;
        $this->validationErrors = $validationErrors;
        $this->includeOriginalBody = $includeOriginalBody;
    }

    public function getErrorDocument(): ErrorDocumentInterface
    {
        $errorDocument = new ErrorDocument($this->getErrors());

        if ($this->includeOriginalBody) {
            $errorDocument->setMeta(["original" => json_decode($this->response->getBody()->__toString(), true)]);
        }

        return $errorDocument;
    }

    protected function getErrors(): array
    {
        $errors = [];
        foreach ($this->validationErrors as $validationError) {
            $error = Error::create()
                ->setStatus("500")
                ->setCode("RESPONSE_BODY_INVALID_JSON_API")
                ->setTitle("Response body is an invalid JSON:API document")
                ->setDetail(ucfirst($validationError["message"]));

            if (isset($validationError["property"]) && $validationError["property"] !== "") {
                $error->setSource(ErrorSource::fromPointer($validationError["property"]));
            }

            $errors[] = $error;
        }

        return $errors;
    }

    public function getValidationErrors(): array
    {
        return $this->validationErrors;
    }
}
