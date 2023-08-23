<?php

declare(strict_types=1);

namespace Devleand\Yin\JsonApi\Exception;

use Devleand\Yin\JsonApi\Schema\Error\Error;
use Devleand\Yin\JsonApi\Schema\Error\ErrorSource;

class QueryParamUnrecognized extends AbstractJsonApiException
{
    /**
     * @var string
     */
    protected $unrecognizedQueryParam;

    public function __construct(string $unrecognizedQueryParam)
    {
        parent::__construct("Query parameter '$unrecognizedQueryParam' can't be recognized!", 400);
        $this->unrecognizedQueryParam = $unrecognizedQueryParam;
    }

    protected function getErrors(): array
    {
        return [
            Error::create()
                ->setStatus("400")
                ->setCode("QUERY_PARAM_UNRECOGNIZED")
                ->setTitle("Query parameter is unrecognized")
                ->setDetail("Query parameter '$this->unrecognizedQueryParam' can't be recognized by the endpoint!")
                ->setSource(ErrorSource::fromParameter($this->unrecognizedQueryParam)),
        ];
    }

    public function getUnrecognizedQueryParam(): string
    {
        return $this->unrecognizedQueryParam;
    }
}
