<?php

declare(strict_types=1);

namespace Devleand\Yin\Tests\JsonApi\Double;

use Devleand\Yin\JsonApi\Schema\Pagination\CursorBasedPaginationLinkProviderTrait;

class StubCursorBasedPaginationProvider
{
    use CursorBasedPaginationLinkProviderTrait;

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
     * @var int
     */
    private $size;

    /**
     * @param mixed $firstItem
     * @param mixed $lastItem
     * @param mixed $currentItem
     * @param mixed $previousItem
     * @param mixed $nextItem
     */
    public function __construct($firstItem, $lastItem, $currentItem, $previousItem, $nextItem, int $size)
    {
        $this->firstItem = $firstItem;
        $this->lastItem = $lastItem;
        $this->currentItem = $currentItem;
        $this->previousItem = $previousItem;
        $this->nextItem = $nextItem;
        $this->size = $size;
    }

    /**
     * @return mixed
     */
    public function getFirstItem()
    {
        return $this->firstItem;
    }

    /**
     * @return mixed
     */
    public function getLastItem()
    {
        return $this->lastItem;
    }

    /**
     * @return mixed
     */
    public function getCurrentItem()
    {
        return $this->currentItem;
    }

    /**
     * @return mixed
     */
    public function getPreviousItem()
    {
        return $this->previousItem;
    }

    /**
     * @return mixed
     */
    public function getNextItem()
    {
        return $this->nextItem;
    }

    public function getSize(): int
    {
        return $this->size;
    }
}
