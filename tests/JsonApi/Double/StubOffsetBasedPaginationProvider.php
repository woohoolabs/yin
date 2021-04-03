<?php

declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Double;

use WoohooLabs\Yin\JsonApi\Schema\Pagination\OffsetBasedPaginationLinkProviderTrait;

class StubOffsetBasedPaginationProvider
{
    use OffsetBasedPaginationLinkProviderTrait;

    /** @var int */
    private $totalItems;

    /** @var int */
    private $offset;

    /** @var int */
    private $limit;

    public function __construct(int $totalItems, int $offset, int $limit)
    {
        $this->totalItems = $totalItems;
        $this->offset = $offset;
        $this->limit = $limit;
    }

    public function getTotalItems(): int
    {
        return $this->totalItems;
    }

    protected function getOffset(): int
    {
        return $this->offset;
    }

    protected function getLimit(): int
    {
        return $this->limit;
    }
}
