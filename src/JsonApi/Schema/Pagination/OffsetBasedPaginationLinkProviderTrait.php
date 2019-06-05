<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema\Pagination;

use WoohooLabs\Yin\JsonApi\Request\Pagination\OffsetBasedPagination;
use WoohooLabs\Yin\JsonApi\Schema\Link\Link;
use WoohooLabs\Yin\Utils;
use function max;

trait OffsetBasedPaginationLinkProviderTrait
{
    abstract protected function getTotalItems(): int;

    abstract protected function getOffset(): int;

    abstract protected function getLimit(): int;

    public function getSelfLink(string $uri, string $queryString): ?Link
    {
        $offset = $this->getOffset();

        if ($offset < 0 || $offset >= $this->getTotalItems()) {
            return null;
        }

        return $this->createPaginatedLink($uri, $queryString, $this->getOffset(), $this->getLimit());
    }

    public function getFirstLink(string $uri, string $queryString): ?Link
    {
        return $this->createPaginatedLink($uri, $queryString, 0, $this->getLimit());
    }

    public function getLastLink(string $uri, string $queryString): ?Link
    {
        return $this->createPaginatedLink($uri, $queryString, max($this->getTotalItems() - $this->getLimit(), 0), $this->getLimit());
    }

    public function getPrevLink(string $uri, string $queryString): ?Link
    {
        if ($this->getOffset() <= 0 || $this->getOffset() + $this->getLimit() > $this->getTotalItems()) {
            return null;
        }

        if ($this->getOffset() - $this->getLimit() > 0) {
            $prevOffset = $this->getOffset() - $this->getLimit();
        } else {
            $prevOffset = 0;
        }

        return $this->createPaginatedLink($uri, $queryString, $prevOffset, $this->getLimit());
    }

    public function getNextLink(string $uri, string $queryString): ?Link
    {
        if ($this->getOffset() < 0 || $this->getOffset() + $this->getLimit() >= $this->getTotalItems()) {
            return null;
        }

        return $this->createPaginatedLink($uri, $queryString, $this->getOffset() + $this->getLimit(), $this->getLimit());
    }

    protected function createPaginatedLink(string $uri, string $queryString, int $offset, int $limit): ?Link
    {
        if ($this->getTotalItems() <= 0 || $this->getLimit() <= 0) {
            return null;
        }

        return new Link(
            Utils::getUri($uri, $queryString, OffsetBasedPagination::getPaginationQueryString($offset, $limit))
        );
    }
}
