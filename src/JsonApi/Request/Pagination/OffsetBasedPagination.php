<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Request\Pagination;

use WoohooLabs\Yin\Utils;
use function urlencode;

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

    public static function fromPaginationQueryParams(
        array $paginationQueryParams,
        int $defaultOffset = 0,
        int $defaultLimit = 0
    ): OffsetBasedPagination {
        return new OffsetBasedPagination(
            Utils::getIntegerFromQueryParam($paginationQueryParams, "offset", $defaultOffset),
            Utils::getIntegerFromQueryParam($paginationQueryParams, "limit", $defaultLimit)
        );
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
        return urlencode("page[offset]=$offset") . "&" . urlencode("page[limit]=$limit");
    }
}
