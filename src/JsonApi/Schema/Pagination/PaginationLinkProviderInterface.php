<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema\Pagination;

use WoohooLabs\Yin\JsonApi\Schema\Link;

interface PaginationLinkProviderInterface
{
    public function getSelfLink(string $uri): ?Link;

    public function getFirstLink(string $uri): ?Link;

    public function getLastLink(string $uri): ?Link;

    public function getPrevLink(string $uri): ?Link;

    public function getNextLink(string $uri): ?Link;
}
