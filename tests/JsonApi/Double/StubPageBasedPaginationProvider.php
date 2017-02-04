<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Double;

use WoohooLabs\Yin\JsonApi\Schema\Pagination\PageBasedPaginationLinkProviderTrait;

class StubPageBasedPaginationProvider
{
    use PageBasedPaginationLinkProviderTrait;

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

    /**
     * @param int $totalItems
     * @param int $page
     * @param int $size
     */
    public function __construct($totalItems, $page, $size)
    {
        $this->totalItems = $totalItems;
        $this->page = $page;
        $this->size = $size;
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
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @inheritDoc
     */
    public function getSize()
    {
        return $this->size;
    }
}
