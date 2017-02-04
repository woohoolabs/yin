<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Exception;

use WoohooLabs\Yin\JsonApi\Schema\Error;
use WoohooLabs\Yin\JsonApi\Schema\ErrorSource;

class SortParamUnrecognized extends JsonApiException
{
    protected $sortParam;

    /**
     * @param string $sortParam
     */
    public function __construct($sortParam)
    {
        parent::__construct("Sorting parameter '$sortParam' , can't be recognized!");
        $this->sortParam = $sortParam;
    }

    /**
     * @inheritDoc
     */
    protected function getErrors()
    {
        return [
            Error::create()
                ->setStatus(400)
                ->setCode("SORTING_UNRECOGNIZED")
                ->setTitle("Sorting paramter is unrecognized")
                ->setDetail("Sorting parameter '$this->sortParam' can't be recognized by the endpoint!")
                ->setSource(ErrorSource::fromParameter("sort"))
        ];
    }

    /**
     * @return string
     */
    public function getSortParam()
    {
        return $this->sortParam;
    }
}
