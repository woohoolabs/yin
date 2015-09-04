<?php
namespace WoohooLabs\Yin\JsonApi\Request\Pagination;

class FixedPagePagination
{
    /**
     * @var int|null
     */
    protected $page;

    /**
     * @param array $paginationQueryParams
     * @param mixed $defaultPage
     * @return $this
     */
    public static function fromPaginationQueryParams(array $paginationQueryParams, $defaultPage = null)
    {
        $page = isset($paginationQueryParams["number"]) ? $paginationQueryParams["number"] : $defaultPage;

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
     * @param int $page
     * @return string
     */
    public static function getPaginationQueryString($page)
    {
        return "page[number]=$page";
    }
}
