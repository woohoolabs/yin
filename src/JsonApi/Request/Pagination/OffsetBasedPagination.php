<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Request\Pagination;

class OffsetBasedPagination
{
    /**
     * @var int
     */
    protected $offset;

    /**
     * @var int
     */
    protected $limit;

    /**
     * @return $this
     */
    public static function fromPaginationQueryParams(
        array $paginationQueryParams,
        int $defaultOffset = 0,
        int $defaultLimit = 0
    ): OffsetBasedPagination {
        $offset = empty($paginationQueryParams["offset"]) ? $defaultOffset : (int) $paginationQueryParams["offset"];
        $limit = empty($paginationQueryParams["limit"]) ? $defaultLimit : (int) $paginationQueryParams["limit"];

        return new OffsetBasedPagination($offset, $limit);
    }

    public function __construct(int $offset, int $limit)
    {
        $this->offset = $offset;
        $this->limit = $limit;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public static function getPaginationQueryString(int $offset, int $limit): string
    {
        return "page[offset]=$offset&page[limit]=$limit";
    }
}
