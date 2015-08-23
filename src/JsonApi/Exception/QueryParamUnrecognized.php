<?php
namespace WoohooLabs\Yin\JsonApi\Exception;

class QueryParamUnrecognized extends \Exception
{
    /**
     * @var string
     */
    private $queryParam;

    /**
     * @param string $queryParam
     */
    public function __construct($queryParam)
    {
        parent::__construct("Query parameter '$queryParam' can't be recognized!");
        $this->queryParam = $queryParam;
    }

    /**
     * @return string
     */
    public function getQueryParam()
    {
        return $this->queryParam;
    }
}
