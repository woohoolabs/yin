<?php
namespace WoohooLabs\Yin\JsonApi\Exception;

class SortParamUnrecognized extends \Exception
{
    private $sortParam;

    /**
     * @param string $sortParam
     */
    public function __construct($sortParam)
    {
        parent::__construct("Sorting parameter '$sortParam' can't be recognized!");
        $this->sortParam = $sortParam;
    }

    /**
     * @return string
     */
    public function getSortParam()
    {
        return $this->sortParam;
    }
}
