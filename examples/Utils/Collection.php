<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Examples\Utils;

use ArrayIterator;
use IteratorAggregate;
use Traversable;
use WoohooLabs\Yin\JsonApi\Schema\Pagination\PageBasedPaginationLinkProviderTrait;
use WoohooLabs\Yin\JsonApi\Schema\Pagination\PaginationLinkProviderInterface;

class Collection implements IteratorAggregate, PaginationLinkProviderInterface
{
    use PageBasedPaginationLinkProviderTrait;

    /**
     * @var ArrayIterator
     */
    private $items;

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
     * @param array $items
     * @param int $totalItems
     * @param int $page
     * @param int $size
     */
    public function __construct(array $items, $totalItems = 0, $page = 0, $size = 0)
    {
        $this->items = new ArrayIterator($items);
        $this->totalItems = $totalItems;
        $this->page = $page;
        $this->size = $size;
    }

    /**
     * @return Traversable
     */
    public function getIterator()
    {
        return $this->items;
    }

    /**
     * @param mixed $item
     */
    public function addItem($item)
    {
        $this->items->append($item);
    }

    /**
     * @return ArrayIterator
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param ArrayIterator $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

    /**
     * @return int
     */
    public function getTotalItems()
    {
        return $this->totalItems;
    }

    /**
     * @param int $totalItems
     */
    public function setTotalItems($totalItems)
    {
        $this->totalItems = $totalItems;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage($page)
    {
        $this->page = $page;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param int $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }
}
