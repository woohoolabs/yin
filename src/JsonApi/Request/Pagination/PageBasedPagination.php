<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Request\Pagination;

class PageBasedPagination
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
     * @return $this
     */
    public static function fromPaginationQueryParams(
        array $paginationQueryParams,
        int $defaultPage = null,
        int $defaultSize = null
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
     * @param int|null $page
     * @param int|null $size
     */
    public static function getPaginationQueryString($page, $size): string
    {
        return "page[number]=$page&page[size]=$size";
    }
}
