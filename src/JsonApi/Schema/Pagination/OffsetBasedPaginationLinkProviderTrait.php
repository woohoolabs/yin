<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema\Pagination;

use WoohooLabs\Yin\JsonApi\Request\Pagination\OffsetBasedPagination;
use WoohooLabs\Yin\JsonApi\Schema\Link;

trait OffsetBasedPaginationLinkProviderTrait
{
    abstract protected function getTotalItems(): int;

    abstract protected function getOffset(): int;

    abstract protected function getLimit(): int;

    public function getSelfLink(string $url): ?Link
    {
        $offset = $this->getOffset();

        if ($offset < 0 || $offset >= $this->getTotalItems()) {
            return null;
        }

        return $this->createPaginatedLink($url, $this->getOffset(), $this->getLimit());
    }

    public function getFirstLink(string $url): ?Link
    {
        return $this->createPaginatedLink($url, 0, $this->getLimit());
    }

    public function getLastLink(string $url): ?Link
    {
        return $this->createPaginatedLink($url, $this->getTotalItems() - $this->getLimit() - 1, $this->getLimit());
    }

    public function getPrevLink(string $url): ?Link
    {
        if ($this->getOffset() <= 0 || $this->getOffset() + $this->getLimit() >= $this->getTotalItems()) {
            return null;
        }

        if ($this->getOffset() - $this->getLimit() > 0) {
            $prevOffset = $this->getOffset() - $this->getLimit();
        } else {
            $prevOffset = 0;
        }

        return $this->createPaginatedLink($url, $prevOffset, $this->getLimit());
    }

    public function getNextLink(string $url): ?Link
    {
        if ($this->getOffset() < 0 || $this->getOffset() + $this->getLimit() >= $this->getTotalItems()) {
            return null;
        }

        return $this->createPaginatedLink($url, $this->getOffset() + $this->getLimit(), $this->getLimit());
    }

    protected function createPaginatedLink(string $url, int $page, int $size): ?Link
    {
        if ($this->getTotalItems() <= 0 || $this->getLimit() <= 0) {
            return null;
        }

        return new Link($this->appendQueryStringToUrl($url, OffsetBasedPagination::getPaginationQueryString($page, $size)));
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
}
