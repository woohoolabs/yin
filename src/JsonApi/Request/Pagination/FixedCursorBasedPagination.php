<?php

declare(strict_types=1);

namespace Devleand\Yin\JsonApi\Request\Pagination;

use function http_build_query;

class FixedCursorBasedPagination
{
    /**
     * @var mixed|null
     */
    protected $cursor;

    /**
     * @param mixed $defaultCursor
     */
    public static function fromPaginationQueryParams(array $paginationQueryParams, $defaultCursor = null): FixedCursorBasedPagination
    {
        return new FixedCursorBasedPagination(
            $paginationQueryParams["cursor"] ?? $defaultCursor
        );
    }

    /**
     * @param mixed $cursor
     */
    public function __construct($cursor)
    {
        $this->cursor = $cursor;
    }

    /**
     * @return mixed
     */
    public function getCursor()
    {
        return $this->cursor;
    }

    /**
     * @param mixed $cursor
     */
    public static function getPaginationQueryString($cursor): string
    {
        return http_build_query(static::getPaginationQueryParams($cursor));
    }

    /**
     * @param mixed $cursor
     */
    public static function getPaginationQueryParams($cursor): array
    {
        return [
            "page" => [
                "cursor" => $cursor,
            ],
        ];
    }
}
