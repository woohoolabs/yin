<?php
namespace WoohooLabs\Yin\JsonApi\Request\Pagination;

class CursorBasedPagination
{
    /**
     * @var mixed|null
     */
    protected $cursor;

    /**
     * @param array $paginationQueryParams
     * @param mixed $defaultCursor
     * @return $this
     */
    public static function fromPaginationQueryParams(array $paginationQueryParams, $defaultCursor = null)
    {
        $cursor = empty($paginationQueryParams["cursor"]) ? $defaultCursor : $paginationQueryParams["cursor"];

        return new self($cursor);
    }

    /**
     * @param mixed|null $cursor
     */
    public function __construct($cursor)
    {
        $this->cursor = $cursor;
    }

    /**
     * @return mixed|null
     */
    public function getCursor()
    {
        return $this->cursor;
    }

    /**
     * @param mixed $cursor
     * @return string
     */
    public static function getPaginationQueryString($cursor)
    {
        return "page[cursor]=$cursor";
    }
}
