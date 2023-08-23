<?php

declare(strict_types=1);

namespace Devleand\Yin\Tests\JsonApi\Double;

use Devleand\Yin\JsonApi\Schema\Link\Link;
use Devleand\Yin\JsonApi\Schema\Pagination\PaginationLinkProviderInterface;

class StubPaginationLinkProvider implements PaginationLinkProviderInterface
{
    public function getSelfLink(string $uri, string $queryString): ?Link
    {
        return new Link($uri . "self");
    }

    public function getFirstLink(string $uri, string $queryString): ?Link
    {
        return new Link($uri . "first");
    }

    public function getLastLink(string $uri, string $queryString): ?Link
    {
        return new Link($uri . "last");
    }

    public function getPrevLink(string $uri, string $queryString): ?Link
    {
        return new Link($uri . "prev");
    }

    public function getNextLink(string $uri, string $queryString): ?Link
    {
        return new Link($uri . "next");
    }
}
