<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Exception;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Document\AbstractErrorDocument;
use WoohooLabs\Yin\JsonApi\Document\ErrorDocument;
use WoohooLabs\Yin\JsonApi\Schema\Error;
use WoohooLabs\Yin\JsonApi\Schema\ErrorSource;

class ResponseBodyInvalidJsonApi extends JsonApiException
{
    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @var array
     */
    protected $validationErrors;

    /**
     * @var bool
     */
    protected $includeOriginalBody;

    public function __construct(ResponseInterface $response, array $validationErrors, bool $includeOriginalBody)
    {
        parent::__construct("Response body is an invalid JSON API document: " . print_r($validationErrors, true));
        $this->response = $response;
        $this->validationErrors = $validationErrors;
        $this->includeOriginalBody = $includeOriginalBody;
    }

    protected function createErrorDocument(): AbstractErrorDocument
    {
        $errorDocument = new ErrorDocument();

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
                ->setTitle("Response body is an invalid JSON API document")
                ->setDetail(ucfirst($validationError["message"]));
            if ($validationError["property"]) {
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
