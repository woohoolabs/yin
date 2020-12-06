<?php

declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema\Pagination;

use WoohooLabs\Yin\JsonApi\Request\Pagination\CursorBasedPagination;
use WoohooLabs\Yin\JsonApi\Schema\Link\Link;
use WoohooLabs\Yin\Utils;

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

    abstract public function getSize(): int;

    public function getSelfLink(string $uri, string $queryString): ?Link
    {
        if ($this->getCurrentItem() === null) {
            return null;
        }

        return $this->createPaginatedLink($uri, $queryString, $this->getCurrentItem(), $this->getSize());
    }

    public function getFirstLink(string $uri, string $queryString): ?Link
    {
        return $this->createPaginatedLink($uri, $queryString, $this->getFirstItem(), $this->getSize());
    }

    public function getLastLink(string $uri, string $queryString): ?Link
    {
        return $this->createPaginatedLink($uri, $queryString, $this->getLastItem(), $this->getSize());
    }

    public function getPrevLink(string $uri, string $queryString): ?Link
    {
        return $this->createPaginatedLink($uri, $queryString, $this->getPreviousItem(), $this->getSize());
    }

    public function getNextLink(string $uri, string $queryString): ?Link
    {
        return $this->createPaginatedLink($uri, $queryString, $this->getNextItem(), $this->getSize());
    }

    /**
     * @param mixed $cursor
     */
    protected function createPaginatedLink(string $uri, string $queryString, $cursor, int $size): ?Link
    {
        if ($cursor === null) {
            return null;
        }

        return new Link(
            Utils::getUri($uri, $queryString, CursorBasedPagination::getPaginationQueryParams($cursor, $size))
        );
    }
}
