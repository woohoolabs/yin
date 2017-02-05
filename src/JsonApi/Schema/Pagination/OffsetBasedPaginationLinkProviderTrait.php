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

    /**
     * @return Link|null
     */
    public function getSelfLink(string $url)
    {
        $offset = $this->getOffset();

        if ($offset < 0 || $offset >= $this->getTotalItems()) {
            return null;
        }

        return $this->createPaginatedLink($url, $this->getOffset(), $this->getLimit());
    }

    /**
     * @return Link|null
     */
    public function getFirstLink(string $url)
    {
        return $this->createPaginatedLink($url, 0, $this->getLimit());
    }

    /**
     * @return Link|null
     */
    public function getLastLink(string $url)
    {
        return $this->createPaginatedLink($url, $this->getTotalItems() - $this->getLimit() - 1, $this->getLimit());
    }

    /**
     * @return Link|null
     */
    public function getPrevLink(string $url)
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

    /**
     * @return Link|null
     */
    public function getNextLink(string $url)
    {
        if ($this->getOffset() < 0 || $this->getOffset() + $this->getLimit() >= $this->getTotalItems()) {
            return null;
        }

        return $this->createPaginatedLink($url, $this->getOffset() + $this->getLimit(), $this->getLimit());
    }

    /**
     * @return Link|null
     */
    protected function createPaginatedLink(string $url, int $page, int $size)
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
