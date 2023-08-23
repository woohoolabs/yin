<?php

declare(strict_types=1);

namespace Devleand\Yin\JsonApi\Exception;

use Devleand\Yin\JsonApi\Schema\Error\Error;
use Devleand\Yin\JsonApi\Schema\Error\ErrorSource;

class QueryParamMalformed extends AbstractJsonApiException
{
    /**
     * @var string
     */
    protected $malformedQueryParam;

    /**
     * @var mixed
     */
    protected $malformedQueryParamValue;

    /**
     * @param mixed $malformedQueryParamValue
     */
    public function __construct(string $malformedQueryParam, $malformedQueryParamValue)
    {
        parent::__construct("Query parameter '$malformedQueryParam' is malformed!", 400);
        $this->malformedQueryParam = $malformedQueryParam;
        $this->malformedQueryParamValue = $malformedQueryParamValue;
    }

    protected function getErrors(): array
    {
        return [
            Error::create()
                ->setStatus("400")
                ->setCode("QUERY_PARAM_MALFORMED")
                ->setTitle("Query parameter is malformed")
                ->setDetail("Query parameter '$this->malformedQueryParam' is malformed!")
                ->setSource(ErrorSource::fromParameter($this->malformedQueryParam)),
        ];
    }

    public function getMalformedQueryParam(): string
    {
        return $this->malformedQueryParam;
    }

    /**
     * @return mixed
     */
    public function getMalformedQueryParamValue()
    {
        return $this->malformedQueryParamValue;
    }
}
