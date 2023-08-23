<?php

declare(strict_types=1);

namespace Devleand\Yin\JsonApi\Exception;

use Devleand\Yin\JsonApi\Schema\Error\Error;
use Devleand\Yin\JsonApi\Schema\Error\ErrorSource;

class DataMemberMissing extends AbstractJsonApiException
{
    public function __construct()
    {
        parent::__construct("Missing `data` member at the document's top level!", 400);
    }

    protected function getErrors(): array
    {
        return [
            Error::create()
                ->setStatus("400")
                ->setCode("DATA_MEMBER_MISSING")
                ->setTitle("Missing `data` member at the document's top level")
                ->setDetail($this->getMessage())
                ->setSource(ErrorSource::fromPointer("")),
        ];
    }
}
