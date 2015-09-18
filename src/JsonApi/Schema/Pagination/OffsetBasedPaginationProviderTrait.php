<?php
namespace WoohooLabs\Yin\JsonApi\Schema\Pagination;

use WoohooLabs\Yin\JsonApi\Request\Pagination\OffsetPagination;
use WoohooLabs\Yin\JsonApi\Schema\Link;

trait OffsetBasedPaginationProviderTrait
{
    /**
     * @return int
     */
    abstract protected function getTotalItems();

    /**
     * @return int
     */
    abstract protected function getOffset();

    /**
     * @return int
     */
    abstract protected function getLimit();

    /**
     * @param string $url
     * @return \WoohooLabs\Yin\JsonApi\Schema\Link|null
     */
    public function getSelfLink($url)
    {
        if ($this->getOffset() < 0 || $this->getOffset() >= $this->getTotalItems()) {
            return null;
        }

        return $this->createPaginatedLink($url, $this->getOffset(), $this->getLimit());
    }

    /**
     * @param string $url
     * @return \WoohooLabs\Yin\JsonApi\Schema\Link|null
     */
    public function getFirstLink($url)
    {
        return $this->createPaginatedLink($url, 0, $this->getLimit());
    }

    /**
     * @param string $url
     * @return \WoohooLabs\Yin\JsonApi\Schema\Link|null
     */
    public function getLastLink($url)
    {
        return $this->createPaginatedLink($url, $this->getTotalItems() - $this->getLimit() - 1, $this->getLimit());
    }

    /**
     * @param string $url
     * @return \WoohooLabs\Yin\JsonApi\Schema\Link|null
     */
    public function getPrevLink($url)
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
     * @param string $url
     * @return \WoohooLabs\Yin\JsonApi\Schema\Link|null
     */
    public function getNextLink($url)
    {
        if ($this->getOffset() < 0 || $this->getOffset() + $this->getLimit() >= $this->getTotalItems()) {
            return null;
        }

        return $this->createPaginatedLink($url, $this->getOffset() + $this->getLimit(), $this->getLimit());
    }

    /**
     * @param string $url
     * @param int $page
     * @param int $size
     * @return \WoohooLabs\Yin\JsonApi\Schema\Link|null
     */
    protected function createPaginatedLink($url, $page, $size)
    {
        if ($this->getTotalItems() <= 0 || $this->getLimit() <= 0) {
            return null;
        }

        return new Link($this->appendQueryStringToUrl($url, OffsetPagination::getPaginationQueryString($page, $size)));
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
}
