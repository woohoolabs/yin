<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Request\Pagination;

class FixedPageBasedPagination
{
    /**
     * @var int|null
     */
    protected $page;

    /**
     * @return $this
     */
    public static function fromPaginationQueryParams(array $paginationQueryParams, int $defaultPage = null)
    {
        $page = empty($paginationQueryParams["number"]) ? $defaultPage : (int) $paginationQueryParams["number"];

        return new self($page);
    }

    /**
     * @param int|null $page
     */
    public function __construct($page)
    {
        $this->page = $page;
    }

    /**
     * @return int|null
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param int|null $page
     */
    public static function getPaginationQueryString($page): string
    {
        return "page[number]=$page";
    }
}
