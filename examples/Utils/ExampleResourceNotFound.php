<?php

declare(strict_types=1);

namespace WoohooLabs\Yin\Examples\Utils;

use WoohooLabs\Yin\JsonApi\Exception\AbstractJsonApiException;
use WoohooLabs\Yin\JsonApi\Schema\Error\Error;

class ExampleResourceNotFound extends AbstractJsonApiException
{
    public function __construct()
    {
        parent::__construct(
            "The requested resource is not found! " .
            "You can find the supported URIs in the Read Me file at https://github.com/woohoolabs/yin/#how-to-try-it-out."
        );
    }

    protected function getErrors(): array
    {
        return [
            Error::create()
                ->setMeta(
                    [
                        "supported_uris" => "https://github.com/woohoolabs/yin/#how-to-try-it-out",
                    ]
                )
                ->setStatus("404")
                ->setCode("RESOURCE_NOT_FOUND")
                ->setTitle("Resource not found")
                ->setDetail($this->getMessage()),
        ];
    }
}
