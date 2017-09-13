<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema\Pagination;

use WoohooLabs\Yin\JsonApi\Request\Pagination\PageBasedPagination;
use WoohooLabs\Yin\JsonApi\Schema\Link;

trait PageBasedPaginationLinkProviderTrait
{
    abstract public function getTotalItems(): int;

    abstract public function getPage(): int;

    abstract public function getSize(): int;

    public function getSelfLink(string $url): ?Link
    {
        if ($this->getPage() <= 0 || $this->getSize() <= 0 || $this->getPage() > $this->getLastPage()) {
            return null;
        }

        return $this->createPaginatedLink($url, $this->getPage(), $this->getSize());
    }

    public function getFirstLink(string $url): ?Link
    {
        return $this->createPaginatedLink($url, 1, $this->getSize());
    }

    public function getLastLink(string $url): ?Link
    {
        if ($this->getSize() <= 0) {
            return null;
        }

        $page = $this->getLastPage();
        return $this->createPaginatedLink($url, $page, $this->getSize());
    }

    public function getPrevLink(string $url): ?Link
    {
        if ($this->getPage() <= 1 || $this->getSize() <= 0) {
            return null;
        }

        return $this->createPaginatedLink($url, $this->getPage() - 1, $this->getSize());
    }

    public function getNextLink(string $url): ?Link
    {
        if ($this->getPage() <= 0 || $this->getSize() <= 0 || $this->getPage() >= $this->getLastPage()) {
            return null;
        }

        return $this->createPaginatedLink($url, $this->getPage() + 1, $this->getSize());
    }

    protected function createPaginatedLink(string $url, int $page, int $size): ?Link
    {
        if ($this->getTotalItems() <= 0 || $this->getSize() <= 0) {
            return null;
        }

        return new Link($this->appendQueryStringToUrl($url, PageBasedPagination::getPaginationQueryString($page, $size)));
    }

    protected function appendQueryStringToUrl(string $url, string $queryString): string
    {
        if (parse_url($url, PHP_URL_QUERY) === null) {
            $separator = substr($url, -1, 1) !== "?" ? "?" : "";
        } else {
            $separator = "&";
        }

        return $url . $separator . $queryString;
    }

    protected function getLastPage(): int
    {
        return (int) ceil($this->getTotalItems() / $this->getSize());
    }
}
