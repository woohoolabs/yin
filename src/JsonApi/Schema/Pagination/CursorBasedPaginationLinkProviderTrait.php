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

    /**
     * @return Link|null
     */
    public function getSelfLink(string $url)
    {
        if ($this->getCurrentItem() === null) {
            return null;
        }

        return $this->createPaginatedLink($url, $this->getCurrentItem());
    }

    /**
     * @return Link|null
     */
    public function getFirstLink(string $url)
    {
        return $this->createPaginatedLink($url, $this->getFirstItem());
    }

    /**
     * @return Link|null
     */
    public function getLastLink(string $url)
    {
        return $this->createPaginatedLink($url, $this->getLastItem());
    }

    /**
     * @return Link|null
     */
    public function getPrevLink(string $url)
    {
        return $this->createPaginatedLink($url, $this->getPreviousItem());
    }

    /**
     * @return Link|null
     */
    public function getNextLink(string $url)
    {
        return $this->createPaginatedLink($url, $this->getNextItem());
    }

    /**
     * @param mixed $cursor
     * @return Link|null
     */
    protected function createPaginatedLink(string $url, $cursor)
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
