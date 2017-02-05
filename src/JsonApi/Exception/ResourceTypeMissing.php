<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Exception;

use WoohooLabs\Yin\JsonApi\Schema\Error;
use WoohooLabs\Yin\JsonApi\Schema\ErrorSource;

class ResourceTypeMissing extends JsonApiException
{
    public function __construct()
    {
        parent::__construct("A resource type must be included in the document!");
    }

    protected function getErrors(): array
    {
        return [
            Error::create()
                ->setStatus("400")
                ->setCode("RESOURCE_TYPE_MISSING")
                ->setTitle("Resource type is missing")
                ->setDetail("A resource type must be included in the document!")
                ->setSource(ErrorSource::fromPointer("/data"))
        ];
    }
}
