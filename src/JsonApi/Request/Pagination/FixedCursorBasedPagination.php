<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Request\Pagination;

use function urlencode;

class FixedCursorBasedPagination
{
    /**
     * @var mixed|null
     */
    protected $cursor;

    /**
     * @param mixed $defaultCursor
     * @return $this
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
        return urlencode("page[cursor]=$cursor");
    }
}
