<?php

declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema\Pagination;

use WoohooLabs\Yin\JsonApi\Request\Pagination\FixedCursorBasedPagination;
use WoohooLabs\Yin\JsonApi\Schema\Link\Link;
use WoohooLabs\Yin\Utils;

trait FixedCursorBasedPaginationLinkProviderTrait
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

    public function getSelfLink(string $uri, string $queryString): ?Link
    {
        if ($this->getCurrentItem() === null) {
            return null;
        }

        return $this->createPaginatedLink($uri, $queryString, $this->getCurrentItem());
    }

    public function getFirstLink(string $uri, string $queryString): ?Link
    {
        return $this->createPaginatedLink($uri, $queryString, $this->getFirstItem());
    }

    public function getLastLink(string $uri, string $queryString): ?Link
    {
        return $this->createPaginatedLink($uri, $queryString, $this->getLastItem());
    }

    public function getPrevLink(string $uri, string $queryString): ?Link
    {
        return $this->createPaginatedLink($uri, $queryString, $this->getPreviousItem());
    }

    public function getNextLink(string $uri, string $queryString): ?Link
    {
        return $this->createPaginatedLink($uri, $queryString, $this->getNextItem());
    }

    /**
     * @param mixed $cursor
     */
    protected function createPaginatedLink(string $uri, string $queryString, $cursor): ?Link
    {
        if ($cursor === null) {
            return null;
        }

        return new Link(
            Utils::getUri($uri, $queryString, FixedCursorBasedPagination::getPaginationQueryParams($cursor))
        );
    }
}
