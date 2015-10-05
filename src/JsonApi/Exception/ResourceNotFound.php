<?php
namespace WoohooLabs\Yin\JsonApi\Exception;

use WoohooLabs\Yin\JsonApi\Schema\Error;
use WoohooLabs\Yin\JsonApi\Schema\ErrorSource;

class ResourceNotFound extends JsonApiException
{
    public function __construct()
    {
        parent::__construct("The requested resource is not found!");
    }

    /**
     * @inheritDoc
     */
    protected function getErrors()
    {
        return [
            Error::create()
                ->setStatus(400)
                ->setCode("RESOURCE_NOT_FOUND")
                ->setTitle("Resource not found")
                ->setDetail($this->getMessage())
        ];
    }
}
