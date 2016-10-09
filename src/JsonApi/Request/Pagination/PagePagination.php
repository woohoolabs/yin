<?php
namespace WoohooLabs\Yin\JsonApi\Request\Pagination;

class PagePagination
{
    /**
     * @var int|null
     */
    protected $page;

    /**
     * @var int|null
     */
    protected $size;

    /**
     * @param array $paginationQueryParams
     * @param mixed $defaultPage
     * @param mixed $defaultSize
     * @return $this
     */
    public static function fromPaginationQueryParams(
        array $paginationQueryParams,
        $defaultPage = null,
        $defaultSize = null
    ) {
        $page = empty($paginationQueryParams["number"]) ? $defaultPage : (int) $paginationQueryParams["number"];
        $size = empty($paginationQueryParams["size"]) ? $defaultSize : (int) $paginationQueryParams["size"];

        return new self($page, $size);
    }

    /**
     * @param int|null $page
     * @param int|null $size
     */
    public function __construct($page, $size)
    {
        $this->page = $page;
        $this->size = $size;
    }

    /**
     * @return int|null
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @return int|null
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param int $page
     * @param int $size
     * @return string
     */
    public static function getPaginationQueryString($page, $size)
    {
        return "page[number]=$page&page[size]=$size";
    }
}
