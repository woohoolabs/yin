<?php

declare(strict_types=1);

namespace Devleand\Yin\Examples\Utils;

use ArrayIterator;
use IteratorAggregate;
use Devleand\Yin\JsonApi\Schema\Pagination\PageBasedPaginationLinkProviderTrait;
use Devleand\Yin\JsonApi\Schema\Pagination\PaginationLinkProviderInterface;

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

    public function __construct(array $items, int $totalItems = 0, int $page = 0, int $size = 0)
    {
        $this->items = new ArrayIterator($items);
        $this->totalItems = $totalItems;
        $this->page = $page;
        $this->size = $size;
    }

    public function getIterator(): iterable
    {
        return $this->items;
    }

    /**
     * @param mixed $item
     */
    public function addItem($item): void
    {
        $this->items->append($item);
    }

    public function setItems(ArrayIterator $items): void
    {
        $this->items = $items;
    }

    public function getTotalItems(): int
    {
        return $this->totalItems;
    }

    public function setTotalItems(int $totalItems): void
    {
        $this->totalItems = $totalItems;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function setSize(int $size): void
    {
        $this->size = $size;
    }
}
