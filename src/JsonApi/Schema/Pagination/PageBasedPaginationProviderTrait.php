<?php
namespace WoohooLabs\Yin\JsonApi\Schema\Pagination;

use WoohooLabs\Yin\JsonApi\Request\Pagination\PagePagination;
use WoohooLabs\Yin\JsonApi\Schema\Link;

trait PageBasedPaginationProviderTrait
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

        return $this->createPaginatedLink($url, floor($this->getTotalItems() / $this->getSize()) + 1, $this->getSize());
    }

    /**
     * @param string $url
     * @return \WoohooLabs\Yin\JsonApi\Schema\Link|null
     */
    public function getPrevLink($url)
    {
        if ($this->getPage() <= 1 || $this->getSize() <= 0 || $this->getPage() >= $this->getLastPage()) {
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
        if ($this->getPage() <= 0 || $this->getSize() <= 0 || $this->getPage() + 1 >= $this->getLastPage()) {
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

        return new Link($this->appendQueryStringToUrl($url, PagePagination::getPaginationQueryString($page, $size)));
    }

    /**
     * @param string $url
     * @param string $queryString
     * @return string
     */
    protected function appendQueryStringToUrl($url, $queryString)
    {
        $separator = (parse_url($url, PHP_URL_QUERY) == NULL) ? '?' : '&';
        return $url . $separator . $queryString;
    }

    /**
     * @return int
     */
    protected function getLastPage()
    {
        return floor($this->getTotalItems() / $this->getSize()) + 1;
    }
}
