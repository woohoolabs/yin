<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Request\Pagination;

use WoohooLabs\Yin\Utils;

class FixedPageBasedPagination
{
    /**
     * @var int
     */
    protected $page;

    /**
     * @return $this
     */
    public static function fromPaginationQueryParams(array $paginationQueryParams, int $defaultPage = 0): FixedPageBasedPagination
    {
        return new FixedPageBasedPagination(
            Utils::getIntegerFromQueryParam($paginationQueryParams, "number", $defaultPage)
        );
    }

    public function __construct(int $page)
    {
        $this->page = $page;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public static function getPaginationQueryString(int $page): string
    {
        return "page[number]=$page";
    }
}
