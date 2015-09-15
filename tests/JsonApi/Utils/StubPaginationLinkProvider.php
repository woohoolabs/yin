<?php
namespace WoohooLabsTest\Yin\JsonApi\Utils;

use WoohooLabs\Yin\JsonApi\Schema\Link;
use WoohooLabs\Yin\JsonApi\Schema\Pagination\PaginationLinkProviderInterface;

class StubPaginationLinkProvider implements PaginationLinkProviderInterface
{
    /**
     * @param string $uri
     * @return \WoohooLabs\Yin\JsonApi\Schema\Link|null
     */
    public function getSelfLink($uri)
    {
        return new Link($uri . "self");
    }

    /**
     * @param string $uri
     * @return \WoohooLabs\Yin\JsonApi\Schema\Link|null
     */
    public function getFirstLink($uri)
    {
        return new Link($uri. "first");
    }

    /**
     * @param string $uri
     * @return \WoohooLabs\Yin\JsonApi\Schema\Link|null
     */
    public function getLastLink($uri)
    {
        return new Link($uri. "last");
    }

    /**
     * @param string $uri
     * @return \WoohooLabs\Yin\JsonApi\Schema\Link|null
     */
    public function getPrevLink($uri)
    {
        return new Link($uri. "prev");
    }

    /**
     * @param string $uri
     * @return \WoohooLabs\Yin\JsonApi\Schema\Link|null
     */
    public function getNextLink($uri)
    {
        return new Link($uri. "next");
    }
}
