<?php

declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Exception;

use WoohooLabs\Yin\JsonApi\Schema\Error\Error;
use WoohooLabs\Yin\JsonApi\Schema\Error\ErrorSource;

class ResourceTypeMissing extends AbstractJsonApiException
{
    public function __construct()
    {
        parent::__construct("A resource type must be included in the document!", 400);
    }

    protected function getErrors(): array
    {
        return [
            Error::create()
                ->setStatus("400")
                ->setCode("RESOURCE_TYPE_MISSING")
                ->setTitle("Resource type is missing")
                ->setDetail("A resource type must be included in the document!")
                ->setSource(ErrorSource::fromPointer("/data")),
        ];
    }
}
