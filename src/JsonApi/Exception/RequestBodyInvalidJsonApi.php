<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Exception;

use WoohooLabs\Yin\JsonApi\Document\AbstractErrorDocument;
use WoohooLabs\Yin\JsonApi\Document\ErrorDocument;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Schema\Error;
use WoohooLabs\Yin\JsonApi\Schema\ErrorSource;

class RequestBodyInvalidJsonApi extends JsonApiException
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var array
     */
    protected $validationErrors;

    /**
     * @var bool
     */
    protected $includeOriginalBody;

    public function __construct(RequestInterface $request, array $validationErrors, bool $includeOriginalBody)
    {
        parent::__construct("Request body is an invalid JSON API document!" . print_r($validationErrors, true));
        $this->request = $request;
        $this->validationErrors = $validationErrors;
        $this->includeOriginalBody = $includeOriginalBody;
    }

    protected function createErrorDocument(): AbstractErrorDocument
    {
        $errorDocument = new ErrorDocument();

        if ($this->includeOriginalBody) {
            $errorDocument->setMeta(["original" => json_decode($this->request->getBody()->__toString(), true)]);
        }

        return $errorDocument;
    }

    protected function getErrors(): array
    {
        $errors = [];
        foreach ($this->validationErrors as $validationError) {
            $error = Error::create()
                ->setStatus("400")
                ->setCode("REQUEST_BODY_INVALID_JSON_API")
                ->setTitle("Request body is an invalid JSON API document")
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
