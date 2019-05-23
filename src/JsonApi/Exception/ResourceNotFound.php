<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Exception;

use WoohooLabs\Yin\JsonApi\Schema\Error\Error;

class ResourceNotFound extends AbstractJsonApiException
{
    public function __construct()
    {
        parent::__construct("The requested resource is not found!", 404);
    }

    protected function getErrors(): array
    {
        return [
            Error::create()
                ->setStatus("404")
                ->setCode("RESOURCE_NOT_FOUND")
                ->setTitle("Resource not found")
                ->setDetail($this->getMessage()),
        ];
    }
}
