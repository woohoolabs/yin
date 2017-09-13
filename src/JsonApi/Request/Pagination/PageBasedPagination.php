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
        ?int $defaultPage = null,
        ?int $defaultSize = null
    ) {
        $page = empty($paginationQueryParams["number"]) ? $defaultPage : (int) $paginationQueryParams["number"];
        $size = empty($paginationQueryParams["size"]) ? $defaultSize : (int) $paginationQueryParams["size"];

        return new self($page, $size);
    }

    public function __construct(?int $page, ?int $size)
    {
        $this->page = $page;
        $this->size = $size;
    }

    public function getPage(): ?int
    {
        return $this->page;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public static function getPaginationQueryString(?int $page, ?int $size): string
    {
        return "page[number]=$page&page[size]=$size";
    }
}
