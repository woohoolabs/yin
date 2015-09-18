<?php
namespace WoohooLabsTest\Yin\JsonApi\Schema\Pagination;

use PHPUnit_Framework_TestCase;
use WoohooLabsTest\Yin\JsonApi\Utils\StubOffsetBasedPaginationProvider;

class OffsetBasedPaginationProviderTraitTest extends PHPUnit_Framework_TestCase
{
    public function testGetSelfLinkWhenOffsetIsNegative()
    {
        $url = "http://example.com/api/users";
        $totalItems = 10;
        $offset = -6;
        $limit = 10;

        $provider = $this->createProvider($totalItems, $offset, $limit);
        $this->assertNull($provider->getSelfLink($url));
    }

    public function testGetSelfLinkWhenOffsetIsZero()
    {
        $url = "http://example.com/api/users";
        $totalItems = 10;
        $offset = 0;
        $limit = 10;

        $provider = $this->createProvider($totalItems, $offset, $limit);
        $this->assertEquals("$url?page[offset]=$offset&page[limit]=$limit", $provider->getSelfLink($url)->getHref());
    }

    public function testGetSelfLinkWhenLimitIsNegative()
    {
        $url = "http://example.com/api/users";
        $totalItems = 10;
        $offset = 0;
        $limit = -1;

        $provider = $this->createProvider($totalItems, $offset, $limit);
        $this->assertNull($provider->getSelfLink($url));
    }

    public function testGetSelfLinkWhenLimitIsZero()
    {
        $url = "http://example.com/api/users";
        $totalItems = 10;
        $offset = 1;
        $limit = 0;

        $provider = $this->createProvider($totalItems, $offset, $limit);
        $this->assertNull($provider->getSelfLink($url));
    }

    public function testGetSelfLinkWhenTotalItemsIsNegative()
    {
        $url = "http://example.com/api/users";
        $totalItems = -30;
        $offset = 1;
        $limit = 0;

        $provider = $this->createProvider($totalItems, $offset, $limit);
        $this->assertNull($provider->getSelfLink($url));
    }

    public function testGetSelfLinkWhenTotalItemsIsZero()
    {
        $url = "http://example.com/api/users";
        $totalItems = 0;
        $offset = 0;
        $limit = 10;

        $provider = $this->createProvider($totalItems, $offset, $limit);
        $this->assertNull($provider->getSelfLink($url));
    }

    public function testGetSelfLinkWhenOffsetIsTooMuch()
    {
        $url = "http://example.com/api/users";
        $totalItems = 30;
        $offset = 30;
        $limit = 10;

        $provider = $this->createProvider($totalItems, $offset, $limit);
        $this->assertNull($provider->getSelfLink($url));
    }

    public function testGetSelfLinkWhenOnlyPathIsProvided()
    {
        $url = "http://example.com/api/users";
        $totalItems = 10;
        $offset = 0;
        $limit = 10;

        $provider = $this->createProvider($totalItems, $offset, $limit);
        $this->assertEquals(
            "$url?page[offset]=$offset&page[limit]=$limit",
            $provider->getSelfLink($url)->getHref()
        );
    }

    public function testGetSelfLinkWhenPathWithQueryStringSeparatorIsProvided()
    {
        $url = "http://example.com/api/users?";
        $totalItems = 10;
        $offset = 0;
        $limit = 10;

        $provider = $this->createProvider($totalItems, $offset, $limit);
        $this->assertEquals(
            "{$url}page[offset]=$offset&page[limit]=$limit",
            $provider->getSelfLink($url)->getHref()
        );
    }

    public function testGetSelfLinkWhenPathWithQueryStringIsProvided()
    {
        $url = "http://example.com/api/users?a=b";
        $totalItems = 10;
        $offset = 0;
        $limit = 10;

        $provider = $this->createProvider($totalItems, $offset, $limit);
        $this->assertEquals(
            "{$url}&page[offset]=$offset&page[limit]=$limit",
            $provider->getSelfLink($url)->getHref()
        );
    }

    public function testGetFirstLinkWhenTotalItemsIsZero()
    {
        $url = "http://example.com/api/users";
        $totalItems = 0;
        $offset = 2;
        $limit = 10;

        $provider = $this->createProvider($totalItems, $offset, $limit);
        $this->assertNull($provider->getFirstLink($url));
    }

    public function testGetFirstLinkWhenLimitIsZero()
    {
        $url = "http://example.com/api/users";
        $totalItems = 10;
        $offset = 0;
        $limit = 0;

        $provider = $this->createProvider($totalItems, $offset, $limit);
        $this->assertNull($provider->getFirstLink($url));
    }

    public function testGetFirstLink()
    {
        $url = "http://example.com/api/users";
        $totalItems = 10;
        $offset = 2;
        $limit = 10;

        $provider = $this->createProvider($totalItems, $offset, $limit);
        $this->assertEquals("$url?page[offset]=0&page[limit]=$limit", $provider->getFirstLink($url)->getHref());
    }

    public function testGetLastLink()
    {
        $url = "http://example.com/api/users";
        $totalItems = 50;
        $offset = 2;
        $limit = 10;

        $provider = $this->createProvider($totalItems, $offset, $limit);
        $this->assertEquals("$url?page[offset]=39&page[limit]=$limit", $provider->getLastLink($url)->getHref());
    }

    public function testGetLastLinkWhen()
    {
        $url = "http://example.com/api/users";
        $totalItems = 50;
        $offset = 2;
        $limit = 10;

        $provider = $this->createProvider($totalItems, $offset, $limit);
        $this->assertEquals("$url?page[offset]=39&page[limit]=$limit", $provider->getLastLink($url)->getHref());
    }

    public function testGetPrevLinkWhenOffsetIsZero()
    {
        $url = "http://example.com/api/users";
        $totalItems = 50;
        $offset = 0;
        $limit = 10;

        $provider = $this->createProvider($totalItems, $offset, $limit);
        $this->assertNull($provider->getPrevLink($url));
    }

    public function testGetPrevLinkWhenPageIsTruncated()
    {
        $url = "http://example.com/api/users";
        $totalItems = 50;
        $offset = 9;
        $limit = 10;

        $provider = $this->createProvider($totalItems, $offset, $limit);
        $this->assertEquals("$url?page[offset]=0&page[limit]=$limit", $provider->getPrevLink($url)->getHref());
    }

    public function testGetPrevLink()
    {
        $url = "http://example.com/api/users";
        $totalItems = 50;
        $offset = 10;
        $limit = 10;

        $provider = $this->createProvider($totalItems, $offset, $limit);
        $this->assertEquals("$url?page[offset]=0&page[limit]=$limit", $provider->getPrevLink($url)->getHref());
    }

    public function testGetPrevLinkWhenOffsetIsMoreThanLimit()
    {
        $url = "http://example.com/api/users";
        $totalItems = 50;
        $offset = 16;
        $limit = 10;

        $provider = $this->createProvider($totalItems, $offset, $limit);
        $this->assertEquals("$url?page[offset]=6&page[limit]=$limit", $provider->getPrevLink($url)->getHref());
    }

    public function testGetNextLinkWhenOffsetIsLast()
    {
        $url = "http://example.com/api/users";
        $totalItems = 50;
        $offset = 41;
        $limit = 10;

        $provider = $this->createProvider($totalItems, $offset, $limit);
        $this->assertNull($provider->getNextLink($url));
    }

    public function testGetNextLink()
    {
        $url = "http://example.com/api/users";
        $totalItems = 50;
        $offset = 10;
        $limit = 10;

        $provider = $this->createProvider($totalItems, $offset, $limit);
        $this->assertEquals("$url?page[offset]=20&page[limit]=$limit", $provider->getNextLink($url)->getHref());
    }

    /**
     * @param int $totalItems
     * @param int $offset
     * @param int $limit
     * @return \WoohooLabsTest\Yin\JsonApi\Utils\StubOffsetBasedPaginationProvider
     */
    private function createProvider($totalItems, $offset, $limit)
    {
        return new StubOffsetBasedPaginationProvider($totalItems, $offset, $limit);
    }
}
