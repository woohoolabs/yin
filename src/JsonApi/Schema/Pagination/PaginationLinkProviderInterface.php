<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema\Pagination;

use WoohooLabs\Yin\JsonApi\Schema\Link;

interface PaginationLinkProviderInterface
{
    /**
     * @return Link|null
     */
    public function getSelfLink(string $uri);

    /**
     * @return Link|null
     */
    public function getFirstLink(string $uri);

    /**
     * @return Link|null
     */
    public function getLastLink(string $uri);

    /**
     * @return Link|null
     */
    public function getPrevLink(string $uri);

    /**
     * @return Link|null
     */
    public function getNextLink(string $uri);
}
