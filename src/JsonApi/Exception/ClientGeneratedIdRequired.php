<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Exception;

use WoohooLabs\Yin\JsonApi\Schema\Error;
use WoohooLabs\Yin\JsonApi\Schema\ErrorSource;

class ClientGeneratedIdRequired extends JsonApiException
{
    public function __construct()
    {
        parent::__construct("A client generated ID must be used!");
    }

    protected function getErrors(): array
    {
        return [
            Error::create()
                ->setStatus("403")
                ->setCode("CLIENT_GENERATED_ID_REQUIRED")
                ->setTitle("Required client generated ID")
                ->setDetail($this->getMessage())
                ->setSource(ErrorSource::fromPointer("/data/id"))
        ];
    }
}
