<?php

declare(strict_types=1);

namespace Devleand\Yin\JsonApi\Exception;

use Devleand\Yin\JsonApi\Schema\Error\Error;
use Devleand\Yin\JsonApi\Schema\Error\ErrorSource;

class ResourceIdMissing extends AbstractJsonApiException
{
    public function __construct()
    {
        parent::__construct("A resource ID must be included in the document!", 400);
    }

    protected function getErrors(): array
    {
        return [
            Error::create()
                ->setStatus("400")
                ->setCode("RESOURCE_ID_MISSING")
                ->setTitle("Resource ID is missing")
                ->setDetail("A resource ID must be included in the document!")
                ->setSource(ErrorSource::fromPointer("/data")),
        ];
    }
}
