<?php
namespace WoohooLabs\Yin\JsonApi\Request\Pagination;

class OffsetBasedPagination
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
     * @param mixed $defaultOffset
     * @param mixed $defaultLimit
     * @return $this
     */
    public static function fromPaginationQueryParams(
        array $paginationQueryParams,
        $defaultOffset = null,
        $defaultLimit = null
    ) {
        $offset = empty($paginationQueryParams["offset"]) ? $defaultOffset : (int) $paginationQueryParams["offset"];
        $limit = empty($paginationQueryParams["limit"]) ? $defaultLimit : (int) $paginationQueryParams["limit"];

        return new self($offset, $limit);
    }

    /**
     * @param int|null $offset
     * @param int|null $limit
     */
    public function __construct($offset, $limit)
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

    /**
     * @param int $offset
     * @param int $limit
     * @return string
     */
    public static function getPaginationQueryString($offset, $limit)
    {
        return "page[offset]=$offset&page[limit]=$limit";
    }
}
