<?php
namespace WoohooLabs\Yin\JsonApi\Schema\Pagination;

interface PaginationLinkProviderInterface
{
    /**
     * @param string $uri
     * @return \WoohooLabs\Yin\JsonApi\Schema\Link|null
     */
    public function getSelfLink($uri);

    /**
     * @param string $uri
     * @return \WoohooLabs\Yin\JsonApi\Schema\Link|null
     */
    public function getFirstLink($uri);

    /**
     * @param string $uri
     * @return \WoohooLabs\Yin\JsonApi\Schema\Link|null
     */
    public function getLastLink($uri);

    /**
     * @param string $uri
     * @return \WoohooLabs\Yin\JsonApi\Schema\Link|null
     */
    public function getPrevLink($uri);

    /**
     * @param string $uri
     * @return \WoohooLabs\Yin\JsonApi\Schema\Link|null
     */
    public function getNextLink($uri);
}
