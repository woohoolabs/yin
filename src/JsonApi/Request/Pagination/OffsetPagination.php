<?php
namespace WoohooLabs\Yin\JsonApi\Request\Pagination;

class OffsetPagination
{
    /**
     * @var int|null
     */
    protected $offset;

    /**
     * @var int|null
     */
    protected $limit;

    /**
     * @param array $paginationQueryParams
     * @return $this
     */
    public static function fromPaginationQueryParams(array $paginationQueryParams)
    {
        $offset = isset($paginationQueryParams["offset"]) ? $paginationQueryParams["offset"] : null;
        $limit = isset($paginationQueryParams["limit"]) ? $paginationQueryParams["limit"] : null;

        return new self($offset, $limit);
    }

    /**
     * @param int|null $offset
     * @param int|null $limit
     */
    public function construct($offset, $limit)
    {
        $this->offset = $offset;
        $this->limit = $limit;
    }

    /**
     * @return int|null
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @return int|null
     */
    public function getLimit()
    {
        return $this->limit;
    }
}
