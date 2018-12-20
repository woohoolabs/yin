<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Exception;

use WoohooLabs\Yin\JsonApi\Schema\Error\Error;
use WoohooLabs\Yin\JsonApi\Schema\Error\ErrorSource;

class InclusionUnsupported extends AbstractJsonApiException
{
    public function __construct()
    {
        parent::__construct("Inclusion is not supported!");
    }

    protected function getErrors(): array
    {
        return [
            Error::create()
                ->setStatus("400")
                ->setCode("INCLUSION_UNSUPPORTED")
                ->setTitle("Inclusion is unsupported")
                ->setDetail("Inclusion is not supported by the endpoint!")
                ->setSource(ErrorSource::fromParameter("include")),
        ];
    }
}
