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
     * @return $this
     */
    public static function fromPaginationQueryParams(array $paginationQueryParams)
    {
        $cursor = isset($paginationQueryParams["cursor"]) ? $paginationQueryParams["cursor"] : null;

        return new self($cursor);
    }

    /**
     * @param int|null $cursor
     */
    public function construct($cursor)
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
}
