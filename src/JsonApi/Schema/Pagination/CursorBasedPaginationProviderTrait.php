<?php
namespace WoohooLabs\Yin\JsonApi\Schema\Pagination;

use WoohooLabs\Yin\JsonApi\Request\Pagination\CursorPagination;
use WoohooLabs\Yin\JsonApi\Schema\Link;

trait CursorBasedPaginationProviderTrait
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
     * @param string $url
     * @return \WoohooLabs\Yin\JsonApi\Schema\Link
     */
    public function getSelfLink($url)
    {
        if ($this->getCurrentItem() === null) {
            return null;
        }

        return $this->createPaginatedLink($url, $this->getCurrentItem());
    }

    /**
     * @param string $url
     * @return \WoohooLabs\Yin\JsonApi\Schema\Link
     */
    public function getFirstLink($url)
    {
        return $this->createPaginatedLink($url, $this->getFirstItem());
    }

    /**
     * @param string $url
     * @return \WoohooLabs\Yin\JsonApi\Schema\Link|null
     */
    public function getLastLink($url)
    {
        return $this->createPaginatedLink($url, $this->getLastItem());
    }

    /**
     * @param string $url
     * @return \WoohooLabs\Yin\JsonApi\Schema\Link|null
     */
    public function getPrevLink($url)
    {
        return $this->createPaginatedLink($url, $this->getPreviousItem());
    }

    /**
     * @param string $url
     * @return \WoohooLabs\Yin\JsonApi\Schema\Link|null
     */
    public function getNextLink($url)
    {
        return $this->createPaginatedLink($url, $this->getNextItem());
    }

    /**
     * @param string $url
     * @param mixed $cursor
     * @return \WoohooLabs\Yin\JsonApi\Schema\Link|null
     */
    protected function createPaginatedLink($url, $cursor)
    {
        if ($cursor === null) {
            return null;
        }

        return new Link($this->appendQueryStringToUrl($url, CursorPagination::getPaginationQueryString($cursor)));
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
}
