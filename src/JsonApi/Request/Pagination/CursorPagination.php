<?php
namespace WoohooLabs\Yin\JsonApi\Request\Pagination;

class CursorPagination
{
    /**
     * @var int|null
     */
    protected $cursor;

    /**
     * @param array $paginationQueryParams
     * @param mixed $defaultCursor
     * @return $this
     */
    public static function fromPaginationQueryParams(array $paginationQueryParams, $defaultCursor = null)
    {
        $cursor = isset($paginationQueryParams["cursor"]) ? $paginationQueryParams["cursor"] : $defaultCursor;

        return new self($cursor);
    }

    /**
     * @param int|null $cursor
     */
    public function __construct($cursor)
    {
        $this->cursor = $cursor;
    }

    /**
     * @return int|null
     */
    public function getCursor()
    {
        return $this->cursor;
    }

    /**
     * @param int $cursor
     * @return string
     */
    public static function getPaginationQueryString($cursor)
    {
        return "page[cursor]=$cursor";
    }
}
