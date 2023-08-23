<?php

declare(strict_types=1);

namespace Devleand\Yin\JsonApi\Schema\Pagination;

use Devleand\Yin\JsonApi\Request\Pagination\PageBasedPagination;
use Devleand\Yin\JsonApi\Schema\Link\Link;
use Devleand\Yin\Utils;

use function ceil;

trait PageBasedPaginationLinkProviderTrait
{
    abstract public function getTotalItems(): int;

    abstract public function getPage(): int;

    abstract public function getSize(): int;

    public function getSelfLink(string $uri, string $queryString): ?Link
    {
        if ($this->getPage() <= 0 || $this->getSize() <= 0 || $this->getPage() > $this->getLastPage()) {
            return null;
        }

        return $this->createPaginatedLink($uri, $queryString, $this->getPage(), $this->getSize());
    }

    public function getFirstLink(string $uri, string $queryString): ?Link
    {
        return $this->createPaginatedLink($uri, $queryString, 1, $this->getSize());
    }

    public function getLastLink(string $uri, string $queryString): ?Link
    {
        if ($this->getSize() <= 0) {
            return null;
        }

        $page = $this->getLastPage();
        return $this->createPaginatedLink($uri, $queryString, $page, $this->getSize());
    }

    public function getPrevLink(string $uri, string $queryString): ?Link
    {
        if ($this->getPage() <= 1 || $this->getSize() <= 0) {
            return null;
        }

        return $this->createPaginatedLink($uri, $queryString, $this->getPage() - 1, $this->getSize());
    }

    public function getNextLink(string $uri, string $queryString): ?Link
    {
        if ($this->getPage() <= 0 || $this->getSize() <= 0 || $this->getPage() >= $this->getLastPage()) {
            return null;
        }

        return $this->createPaginatedLink($uri, $queryString, $this->getPage() + 1, $this->getSize());
    }

    protected function createPaginatedLink(string $uri, string $queryString, int $page, int $size): ?Link
    {
        if ($this->getTotalItems() <= 0 || $this->getSize() <= 0) {
            return null;
        }

        return new Link(
            Utils::getUri($uri, $queryString, PageBasedPagination::getPaginationQueryParams($page, $size))
        );
    }

    protected function getLastPage(): int
    {
        return (int) ceil($this->getTotalItems() / $this->getSize());
    }
}
