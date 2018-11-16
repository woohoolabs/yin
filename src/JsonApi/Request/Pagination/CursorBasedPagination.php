<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Request\Pagination;

class CursorBasedPagination
{
    /**
     * @var mixed|null
     */
    protected $cursor;

    /**
     * @param mixed $defaultCursor
     * @return $this
     */
    public static function fromPaginationQueryParams(array $paginationQueryParams, $defaultCursor = null): CursorBasedPagination
    {
        $cursor = isset($paginationQueryParams["cursor"]) ? $paginationQueryParams["cursor"] : $defaultCursor;

        return new CursorBasedPagination($cursor);
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
        return "page[cursor]=$cursor";
    }
}
