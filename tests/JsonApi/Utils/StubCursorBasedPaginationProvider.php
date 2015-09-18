<?php
namespace WoohooLabsTest\Yin\JsonApi\Utils;

use WoohooLabs\Yin\JsonApi\Schema\Pagination\CursorBasedPaginationProviderTrait;

class StubCursorBasedPaginationProvider
{
    use CursorBasedPaginationProviderTrait;

    /**
     * @var mixed
     */
    private $firstItem;

    /**
     * @var mixed
     */
    private $lastItem;

    /**
     * @var mixed
     */
    private $currentItem;

    /**
     * @var mixed
     */
    private $previousItem;

    /**
     * @var mixed
     */
    private $nextItem;

    /**
     * @param mixed $firstItem
     * @param mixed $lastItem
     * @param mixed $currentItem
     * @param mixed $previousItem
     * @param mixed $nextItem
     */
    public function __construct($firstItem, $lastItem, $currentItem, $previousItem, $nextItem)
    {
        $this->firstItem = $firstItem;
        $this->lastItem = $lastItem;
        $this->currentItem = $currentItem;
        $this->previousItem = $previousItem;
        $this->nextItem = $nextItem;
    }

    /**
     * @inheritDoc
     */
    public function getFirstItem()
    {
        return $this->firstItem;
    }

    /**
     * @inheritDoc
     */
    public function getLastItem()
    {
        return $this->lastItem;
    }

    /**
     * @inheritDoc
     */
    public function getCurrentItem()
    {
        return $this->currentItem;
    }

    /**
     * @inheritDoc
     */
    public function getPreviousItem()
    {
        return $this->previousItem;
    }

    /**
     * @inheritDoc
     */
    public function getNextItem()
    {
        return $this->nextItem;
    }
}
