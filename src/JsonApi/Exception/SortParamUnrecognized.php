<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Exception;

use WoohooLabs\Yin\JsonApi\Schema\Error;
use WoohooLabs\Yin\JsonApi\Schema\ErrorSource;

class SortParamUnrecognized extends JsonApiException
{
    /**
     * @var string
     */
    protected $sortParam;

    public function __construct(string $sortParam)
    {
        parent::__construct("Sorting parameter '$sortParam' , can't be recognized!");
        $this->sortParam = $sortParam;
    }

    protected function getErrors(): array
    {
        return [
            Error::create()
                ->setStatus("400")
                ->setCode("SORTING_UNRECOGNIZED")
                ->setTitle("Sorting paramter is unrecognized")
                ->setDetail("Sorting parameter '$this->sortParam' can't be recognized by the endpoint!")
                ->setSource(ErrorSource::fromParameter("sort"))
        ];
    }

    public function getSortParam(): string
    {
        return $this->sortParam;
    }
}
