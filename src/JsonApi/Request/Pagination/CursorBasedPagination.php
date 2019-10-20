<?php

declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Request\Pagination;

use WoohooLabs\Yin\Utils;

use function urlencode;

class CursorBasedPagination
{
    /**
     * @var mixed|null
     */
    protected $cursor;

    /**
     * @var int
     */
    protected $size;

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
        return urlencode("page[cursor]=$cursor") . "&" . urlencode("page[size]=$size");
    }
}
