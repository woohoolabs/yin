<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Exception;

use WoohooLabs\Yin\JsonApi\Schema\Error;
use WoohooLabs\Yin\JsonApi\Schema\ErrorSource;

class QueryParamMalformed extends JsonApiException
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
        parent::__construct("Query parameter '$malformedQueryParam' is malformed!");
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
                ->setSource(ErrorSource::fromParameter($this->malformedQueryParam))
        ];
    }

    public function getMalformedQueryParam(): string
    {
        return $this->malformedQueryParam;
    }

    public function getMalformedQueryParamValue()
    {
        return $this->malformedQueryParamValue;
    }
}
