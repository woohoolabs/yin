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
     * @return $this
     */
    public static function fromPaginationQueryParams(array $paginationQueryParams)
    {
        $page = isset($paginationQueryParams["page"]) ? $paginationQueryParams["page"] : null;
        $size = isset($paginationQueryParams["size"]) ? $paginationQueryParams["size"] : null;

        return new self($page, $size);
    }

    /**
     * @param int|null $page
     * @param int|null $size
     */
    public function construct($page, $size)
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
}
