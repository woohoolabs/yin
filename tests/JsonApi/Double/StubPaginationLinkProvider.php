<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Double;

use WoohooLabs\Yin\JsonApi\Schema\Link;
use WoohooLabs\Yin\JsonApi\Schema\Pagination\PaginationLinkProviderInterface;

class StubPaginationLinkProvider implements PaginationLinkProviderInterface
{
    public function getSelfLink(string $uri): ?Link
    {
        return new Link($uri . "self");
    }

    public function getFirstLink(string $uri): ?Link
    {
        return new Link($uri . "first");
    }

    public function getLastLink(string $uri): ?Link
    {
        return new Link($uri . "last");
    }

    public function getPrevLink(string $uri): ?Link
    {
        return new Link($uri . "prev");
    }

    public function getNextLink(string $uri): ?Link
    {
        return new Link($uri . "next");
    }
}
