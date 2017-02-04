<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema\Pagination;

use WoohooLabs\Yin\JsonApi\Request\Pagination\PageBasedPagination;
use WoohooLabs\Yin\JsonApi\Schema\Link;

trait PageBasedPaginationLinkProviderTrait
{
    /**
     * @return int
     */
    abstract public function getTotalItems();

    /**
     * @return int
     */
    abstract public function getPage();

    /**
     * @return int
     */
    abstract public function getSize();

    /**
     * @param string $url
     * @return \WoohooLabs\Yin\JsonApi\Schema\Link|null
     */
    public function getSelfLink($url)
    {
        if ($this->getPage() <= 0 || $this->getSize() <= 0 || $this->getPage() > $this->getLastPage()) {
            return null;
        }

        return $this->createPaginatedLink($url, $this->getPage(), $this->getSize());
    }

    /**
     * @param string $url
     * @return \WoohooLabs\Yin\JsonApi\Schema\Link|null
     */
    public function getFirstLink($url)
    {
        return $this->createPaginatedLink($url, 1, $this->getSize());
    }

    /**
     * @param string $url
     * @return \WoohooLabs\Yin\JsonApi\Schema\Link|null
     */
    public function getLastLink($url)
    {
        if ($this->getSize() <= 0) {
            return null;
        }

        $page = $this->getLastPage();
        return $this->createPaginatedLink($url, $page, $this->getSize());
    }

    /**
     * @param string $url
     * @return \WoohooLabs\Yin\JsonApi\Schema\Link|null
     */
    public function getPrevLink($url)
    {
        if ($this->getPage() <= 1 || $this->getSize() <= 0) {
            return null;
        }

        return $this->createPaginatedLink($url, $this->getPage() - 1, $this->getSize());
    }

    /**
     * @param string $url
     * @return \WoohooLabs\Yin\JsonApi\Schema\Link|null
     */
    public function getNextLink($url)
    {
        if ($this->getPage() <= 0 || $this->getSize() <= 0 || $this->getPage() >= $this->getLastPage()) {
            return null;
        }

        return $this->createPaginatedLink($url, $this->getPage() + 1, $this->getSize());
    }

    /**
     * @param string $url
     * @param int $page
     * @param int $size
     * @return \WoohooLabs\Yin\JsonApi\Schema\Link|null
     */
    protected function createPaginatedLink($url, $page, $size)
    {
        if ($this->getTotalItems() <= 0 || $this->getSize() <= 0) {
            return null;
        }

        return new Link($this->appendQueryStringToUrl($url, PageBasedPagination::getPaginationQueryString($page, $size)));
    }

    /**
     * @param string $url
     * @param string $queryString
     * @return string
     */
    protected function appendQueryStringToUrl($url, $queryString)
    {
        if (parse_url($url, PHP_URL_QUERY) === null) {
            $separator = substr($url, -1, 1) !== "?" ? "?" : "";
        } else {
            $separator = "&";
        }

        return $url . $separator . $queryString;
    }

    /**
     * @return float
     */
    protected function getLastPage()
    {
        return ceil($this->getTotalItems() / $this->getSize());
    }
}
