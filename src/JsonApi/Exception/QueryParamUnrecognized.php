<?php
namespace WoohooLabs\Yin\JsonApi\Exception;

use WoohooLabs\Yin\JsonApi\Schema\Error;
use WoohooLabs\Yin\JsonApi\Schema\ErrorSource;

class QueryParamUnrecognized extends JsonApiException
{
    /**
     * @var string
     */
    private $unrecognizedQueryParam;

    /**
     * @param string $unrecognizedQueryParam
     */
    public function __construct($unrecognizedQueryParam)
    {
        parent::__construct("Query parameter '$unrecognizedQueryParam' can't be recognized!");
        $this->unrecognizedQueryParam = $unrecognizedQueryParam;
    }

    /**
     * @inheritDoc
     */
    protected function getErrors()
    {
        return [
            Error::create()
                ->setStatus(400)
                ->setCode("QUERY_PARAM_UNRECOGNIZED")
                ->setTitle("Query parameter is unrecognized")
                ->setDetail("Query parameter '$this->unrecognizedQueryParam' can't be recognized by the endpoint!")
                ->setSource(ErrorSource::fromParameter($this->unrecognizedQueryParam))
        ];
    }

    /**
     * @return string
     */
    public function getUnrecognizedQueryParam()
    {
        return $this->unrecognizedQueryParam;
    }
}
