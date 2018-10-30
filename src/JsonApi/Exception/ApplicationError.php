<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Exception;

class ApplicationError extends JsonApiException
{
    public function __construct()
    {
        parent::__construct("Application exception is thrown!");
    }

    protected function getErrors(): array
    {
        return [
            Error::create()
                ->setStatus("500")
                ->setCode("APPLICATION_ERROR")
                ->setTitle("Application error")
                ->setDetail("An application error has occurred!")
        ];
    }
}
