<?php

declare(strict_types=1);

namespace Devleand\Yin\JsonApi\Exception;

use Devleand\Yin\JsonApi\Schema\Error\Error;
use Devleand\Yin\JsonApi\Schema\Error\ErrorSource;

class SortingUnsupported extends AbstractJsonApiException
{
    public function __construct()
    {
        parent::__construct("Sorting is not supported!", 400);
    }

    protected function getErrors(): array
    {
        return [
            Error::create()
                ->setStatus("400")
                ->setCode("SORTING_UNSUPPORTED")
                ->setTitle("Sorting is unsupported")
                ->setDetail("Sorting is not supported by the endpoint!")
                ->setSource(ErrorSource::fromParameter("sort")),
        ];
    }
}
