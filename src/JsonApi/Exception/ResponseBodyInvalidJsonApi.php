<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Exception;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Document\ErrorDocument;
use WoohooLabs\Yin\JsonApi\Schema\Error;
use WoohooLabs\Yin\JsonApi\Schema\ErrorSource;

class ResponseBodyInvalidJsonApi extends JsonApiException
{
    /**
     * @var \Psr\Http\Message\ResponseInterface
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

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param array $validationErrors
     * @param boolean $includeOriginalBody
     */
    public function __construct(ResponseInterface $response, array $validationErrors, $includeOriginalBody)
    {
        parent::__construct("Response body is an invalid JSON API document: " . print_r($validationErrors, true));
        $this->response = $response;
        $this->validationErrors = $validationErrors;
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
        $errors = [];
        foreach ($this->validationErrors as $validationError) {
            $error = Error::create()
                ->setStatus(500)
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

    /**
     * @return array
     */
    public function getValidationErrors()
    {
        return $this->validationErrors;
    }
}
