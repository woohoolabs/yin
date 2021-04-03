<?php

declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Request\Pagination;

use WoohooLabs\Yin\Utils;

use function http_build_query;

class CursorBasedPagination
{
    /** @var mixed */
    protected $cursor;
    protected int $size;

    /**
     * @param mixed $defaultCursor
     */
    public static function fromPaginationQueryParams(array $paginationQueryParams, $defaultCursor = null, int $defaultSize = 0): CursorBasedPagination
    {
        return new CursorBasedPagination(
            $paginationQueryParams["cursor"] ?? $defaultCursor,
            Utils::getIntegerFromQueryParam($paginationQueryParams, "size", $defaultSize)
        );
    }

    /**
     * @param mixed $cursor
     */
    public function __construct($cursor, int $size = 0)
    {
        $this->cursor = $cursor;
        $this->size = $size;
    }

    /**
     * @return mixed
     */
    public function getCursor()
    {
        return $this->cursor;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @param mixed $cursor
     */
    public static function getPaginationQueryString($cursor, int $size): string
    {
        return http_build_query(static::getPaginationQueryParams($cursor, $size));
    }

    /**
     * @param mixed $cursor
     */
    public static function getPaginationQueryParams($cursor, int $size): array
    {
        return [
            "page" => [
                "cursor" => $cursor,
                "size" => $size,
            ],
        ];
    }
}
