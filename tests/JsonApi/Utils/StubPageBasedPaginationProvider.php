<?php
namespace WoohooLabsTest\Yin\JsonApi\Utils;

use WoohooLabs\Yin\JsonApi\Schema\Pagination\PageBasedPaginationProviderTrait;

class StubPageBasedPaginationProvider
{
    use PageBasedPaginationProviderTrait;

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
