<?php

declare(strict_types=1);

namespace Devleand\Yin\JsonApi\Exception;

use Devleand\Yin\JsonApi\Schema\Error\Error;

class ApplicationError extends AbstractJsonApiException
{
    public function __construct()
    {
        parent::__construct("Application exception is thrown!", 500);
    }

    protected function getErrors(): array
    {
        return [
            Error::create()
                ->setStatus("500")
                ->setCode("APPLICATION_ERROR")
                ->setTitle("Application error")
                ->setDetail("An application error has occurred!"),
        ];
    }
}
