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
    public static function fromPaginationQueryParams(array $paginationQueryParams, ?int $defaultPage = null)
    {
        $page = empty($paginationQueryParams["number"]) ? $defaultPage : (int) $paginationQueryParams["number"];

        return new self($page);
    }

    public function __construct(?int $page)
    {
        $this->page = $page;
    }

    public function getPage(): ?int
    {
        return $this->page;
    }

    public static function getPaginationQueryString(?int $page): string
    {
        return "page[number]=$page";
    }
}
