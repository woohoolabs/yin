<?php
namespace WoohooLabs\Yin\JsonApi\Exception;

class QueryParamUnrecognized extends \Exception
{
    private $queryParam;

    /**
     * @param string $includes
     */
    public function __construct($includes)
    {
        parent::__construct("Query param '$includes' can't be recognized!");
        $this->queryParam = $includes;
    }

    /**
     * @return string
     */
    public function getQueryParam()
    {
        return $this->queryParam;
    }
}
