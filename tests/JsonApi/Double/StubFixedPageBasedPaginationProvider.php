<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Double;

use WoohooLabs\Yin\JsonApi\Schema\Pagination\FixedPageBasedPaginationLinkProviderTrait;

class StubFixedPageBasedPaginationProvider
{
    use FixedPageBasedPaginationLinkProviderTrait;

    /**
     * @var int
     */
    private $totalItems;

    /**
     * @var int
     */
    private $page;

    /**
     * @var int
     */
    private $size;

    public function __construct(int $totalItems, int $page, int $size)
    {
        $this->totalItems = $totalItems;
        $this->page = $page;
        $this->size = $size;
    }

    public function getTotalItems(): int
    {
        return $this->totalItems;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getSize(): int
    {
        return $this->size;
    }
}
