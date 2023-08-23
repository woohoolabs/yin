<?php

declare(strict_types=1);

namespace Devleand\Yin\JsonApi\Exception;

use Devleand\Yin\JsonApi\Schema\Error\Error;
use Devleand\Yin\JsonApi\Schema\Error\ErrorSource;

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
