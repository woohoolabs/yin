<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Double;

use WoohooLabs\Yin\JsonApi\Schema\Pagination\OffsetBasedPaginationLinkProviderTrait;

class StubOffsetBasedPaginationProvider
{
    use OffsetBasedPaginationLinkProviderTrait;

    /**
     * @var int
     */
    private $totalItems;

    /**
     * @var int
     */
    private $offset;

    /**
     * @var int
     */
    private $limit;

    /**
     * @param int $totalItems
     * @param int $offset
     * @param int $limit
     */
    public function __construct($totalItems, $offset, $limit)
    {
        $this->totalItems = $totalItems;
        $this->offset = $offset;
        $this->limit = $limit;
    }

    /**
     * @inheritDoc
     */
    public function getTotalItems()
    {
        return $this->totalItems;
    }

    /**
     * @inheritDoc
     */
    protected function getOffset()
    {
        return $this->offset;
    }

    /**
     * @inheritDoc
     */
    protected function getLimit()
    {
        return $this->limit;
    }
}
