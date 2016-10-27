<?php
namespace WoohooLabsTest\Yin\JsonApi\Double;

use WoohooLabs\Yin\JsonApi\Schema\Link;
use WoohooLabs\Yin\JsonApi\Schema\Pagination\PaginationLinkProviderInterface;

class StubPaginationLinkProvider implements PaginationLinkProviderInterface
{
    /**
     * @inheritDoc
     */
    public function getSelfLink($uri)
    {
        return new Link($uri . "self");
    }

    /**
     * @inheritDoc
     */
    public function getFirstLink($uri)
    {
        return new Link($uri. "first");
    }

    /**
     * @inheritDoc
     */
    public function getLastLink($uri)
    {
        return new Link($uri. "last");
    }

    /**
     * @inheritDoc
     */
    public function getPrevLink($uri)
    {
        return new Link($uri. "prev");
    }

    /**
     * @inheritDoc
     */
    public function getNextLink($uri)
    {
        return new Link($uri. "next");
    }
}
