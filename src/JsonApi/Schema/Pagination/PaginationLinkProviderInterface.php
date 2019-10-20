<?php

declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema\Pagination;

use WoohooLabs\Yin\JsonApi\Schema\Link\Link;

interface PaginationLinkProviderInterface
{
    public function getSelfLink(string $uri, string $queryString): ?Link;

    public function getFirstLink(string $uri, string $queryString): ?Link;

    public function getLastLink(string $uri, string $queryString): ?Link;

    public function getPrevLink(string $uri, string $queryString): ?Link;

    public function getNextLink(string $uri, string $queryString): ?Link;
}
