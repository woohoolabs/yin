<?php
declare(strict_types=1);

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
     * @return $this
     */
    public static function fromPaginationQueryParams(
        array $paginationQueryParams,
        int $defaultOffset = null,
        int $defaultLimit = null
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
     * @param int|null $offset
     * @param int|null $limit
     * @return string
     */
    public static function getPaginationQueryString($offset, $limit): string
    {
        return "page[offset]=$offset&page[limit]=$limit";
    }
}
