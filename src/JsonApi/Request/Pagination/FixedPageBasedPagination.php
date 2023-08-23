<?php

declare(strict_types=1);

namespace Devleand\Yin\JsonApi\Request\Pagination;

use Devleand\Yin\Utils;

use function http_build_query;

class FixedPageBasedPagination
{
    /**
     * @var int
     */
    protected $page;

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
        return http_build_query(static::getPaginationQueryParams($page));
    }

    public static function getPaginationQueryParams(int $page): array
    {
        return [
            "page" => [
                "number" => $page,
            ],
        ];
    }
}
