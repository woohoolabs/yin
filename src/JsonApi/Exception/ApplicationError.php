<?php
namespace WoohooLabs\Yin\JsonApi\Exception;

use WoohooLabs\Yin\JsonApi\Schema\Error;

class ApplicationError extends JsonApiException
{
    public function __construct()
    {
        parent::__construct("Application exception is thrown!");
    }

    /**
     * @inheritDoc
     */
    protected function getErrors()
    {
        return [
            Error::create()
                ->setStatus(400)
                ->setCode("APPLICATION_ERROR")
                ->setTitle("Application error")
                ->setDetail("An application error has occurred!")
        ];
    }
}
