<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema\Pagination;

use WoohooLabs\Yin\JsonApi\Request\Pagination\CursorBasedPagination;
use WoohooLabs\Yin\JsonApi\Schema\Link;

trait CursorBasedPaginationLinkProviderTrait
{
    /**
     * @return mixed
     */
    abstract public function getFirstItem();

    /**
     * @return mixed
     */
    abstract public function getLastItem();

    /**
     * @return mixed
     */
    abstract public function getCurrentItem();

    /**
     * @return mixed
     */
    abstract public function getPreviousItem();

    /**
     * @return mixed
     */
    abstract public function getNextItem();

    public function getSelfLink(string $url): ?Link
    {
        if ($this->getCurrentItem() === null) {
            return null;
        }

        return $this->createPaginatedLink($url, $this->getCurrentItem());
    }

    public function getFirstLink(string $url): ?Link
    {
        return $this->createPaginatedLink($url, $this->getFirstItem());
    }

    public function getLastLink(string $url): ?Link
    {
        return $this->createPaginatedLink($url, $this->getLastItem());
    }

    public function getPrevLink(string $url): ?Link
    {
        return $this->createPaginatedLink($url, $this->getPreviousItem());
    }

    public function getNextLink(string $url): ?Link
    {
        return $this->createPaginatedLink($url, $this->getNextItem());
    }

    /**
     * @param mixed $cursor
     */
    protected function createPaginatedLink(string $url, $cursor): ?Link
    {
        if ($cursor === null) {
            return null;
        }

        return new Link($this->appendQueryStringToUrl($url, CursorBasedPagination::getPaginationQueryString($cursor)));
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
