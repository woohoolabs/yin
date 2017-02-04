<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Schema\Pagination;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\Tests\JsonApi\Double\StubOffsetBasedPaginationProvider;

class OffsetBasedPaginationProviderTraitTest extends TestCase
{
    /**
     * @test
     */
    public function getSelfLinkWhenOffsetIsNegative()
    {
        $url = "http://example.com/api/users";
        $totalItems = 10;
        $offset = -6;
        $limit = 10;

        $provider = $this->createProvider($totalItems, $offset, $limit);
        $this->assertNull($provider->getSelfLink($url));
    }

    /**
     * @test
     */
    public function getSelfLinkWhenOffsetIsZero()
    {
        $url = "http://example.com/api/users";
        $totalItems = 10;
        $offset = 0;
        $limit = 10;

        $provider = $this->createProvider($totalItems, $offset, $limit);
        $this->assertEquals("$url?page[offset]=$offset&page[limit]=$limit", $provider->getSelfLink($url)->getHref());
    }

    /**
     * @test
     */
    public function getSelfLinkWhenLimitIsNegative()
    {
        $url = "http://example.com/api/users";
        $totalItems = 10;
        $offset = 0;
        $limit = -1;

        $provider = $this->createProvider($totalItems, $offset, $limit);
        $this->assertNull($provider->getSelfLink($url));
    }

    /**
     * @test
     */
    public function getSelfLinkWhenLimitIsZero()
    {
        $url = "http://example.com/api/users";
        $totalItems = 10;
        $offset = 1;
        $limit = 0;

        $provider = $this->createProvider($totalItems, $offset, $limit);
        $this->assertNull($provider->getSelfLink($url));
    }

    /**
     * @test
     */
    public function getSelfLinkWhenTotalItemsIsNegative()
    {
        $url = "http://example.com/api/users";
        $totalItems = -30;
        $offset = 1;
        $limit = 0;

        $provider = $this->createProvider($totalItems, $offset, $limit);
        $this->assertNull($provider->getSelfLink($url));
    }

    /**
     * @test
     */
    public function getSelfLinkWhenTotalItemsIsZero()
    {
        $url = "http://example.com/api/users";
        $totalItems = 0;
        $offset = 0;
        $limit = 10;

        $provider = $this->createProvider($totalItems, $offset, $limit);
        $this->assertNull($provider->getSelfLink($url));
    }

    /**
     * @test
     */
    public function getSelfLinkWhenOffsetIsTooMuch()
    {
        $url = "http://example.com/api/users";
        $totalItems = 30;
        $offset = 30;
        $limit = 10;

        $provider = $this->createProvider($totalItems, $offset, $limit);
        $this->assertNull($provider->getSelfLink($url));
    }

    /**
     * @test
     */
    public function getSelfLinkWhenOnlyPathIsProvided()
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

    /**
     * @test
     */
    public function getSelfLinkWhenPathWithQueryStringSeparatorIsProvided()
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

    /**
     * @test
     */
    public function getSelfLinkWhenPathWithQueryStringIsProvided()
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

    /**
     * @test
     */
    public function getFirstLinkWhenTotalItemsIsZero()
    {
        $url = "http://example.com/api/users";
        $totalItems = 0;
        $offset = 2;
        $limit = 10;

        $provider = $this->createProvider($totalItems, $offset, $limit);
        $this->assertNull($provider->getFirstLink($url));
    }

    /**
     * @test
     */
    public function getFirstLinkWhenLimitIsZero()
    {
        $url = "http://example.com/api/users";
        $totalItems = 10;
        $offset = 0;
        $limit = 0;

        $provider = $this->createProvider($totalItems, $offset, $limit);
        $this->assertNull($provider->getFirstLink($url));
    }

    /**
     * @test
     */
    public function getFirstLink()
    {
        $url = "http://example.com/api/users";
        $totalItems = 10;
        $offset = 2;
        $limit = 10;

        $provider = $this->createProvider($totalItems, $offset, $limit);
        $this->assertEquals("$url?page[offset]=0&page[limit]=$limit", $provider->getFirstLink($url)->getHref());
    }

    /**
     * @test
     */
    public function getLastLink()
    {
        $url = "http://example.com/api/users";
        $totalItems = 50;
        $offset = 2;
        $limit = 10;

        $provider = $this->createProvider($totalItems, $offset, $limit);
        $this->assertEquals("$url?page[offset]=39&page[limit]=$limit", $provider->getLastLink($url)->getHref());
    }

    /**
     * @test
     */
    public function getLastLinkWhen()
    {
        $url = "http://example.com/api/users";
        $totalItems = 50;
        $offset = 2;
        $limit = 10;

        $provider = $this->createProvider($totalItems, $offset, $limit);
        $this->assertEquals("$url?page[offset]=39&page[limit]=$limit", $provider->getLastLink($url)->getHref());
    }

    /**
     * @test
     */
    public function getPrevLinkWhenOffsetIsZero()
    {
        $url = "http://example.com/api/users";
        $totalItems = 50;
        $offset = 0;
        $limit = 10;

        $provider = $this->createProvider($totalItems, $offset, $limit);
        $this->assertNull($provider->getPrevLink($url));
    }

    /**
     * @test
     */
    public function getPrevLinkWhenPageIsTruncated()
    {
        $url = "http://example.com/api/users";
        $totalItems = 50;
        $offset = 9;
        $limit = 10;

        $provider = $this->createProvider($totalItems, $offset, $limit);
        $this->assertEquals("$url?page[offset]=0&page[limit]=$limit", $provider->getPrevLink($url)->getHref());
    }

    /**
     * @test
     */
    public function getPrevLink()
    {
        $url = "http://example.com/api/users";
        $totalItems = 50;
        $offset = 10;
        $limit = 10;

        $provider = $this->createProvider($totalItems, $offset, $limit);
        $this->assertEquals("$url?page[offset]=0&page[limit]=$limit", $provider->getPrevLink($url)->getHref());
    }

    /**
     * @test
     */
    public function getPrevLinkWhenOffsetIsMoreThanLimit()
    {
        $url = "http://example.com/api/users";
        $totalItems = 50;
        $offset = 16;
        $limit = 10;

        $provider = $this->createProvider($totalItems, $offset, $limit);
        $this->assertEquals("$url?page[offset]=6&page[limit]=$limit", $provider->getPrevLink($url)->getHref());
    }

    /**
     * @test
     */
    public function getNextLinkWhenOffsetIsLast()
    {
        $url = "http://example.com/api/users";
        $totalItems = 50;
        $offset = 41;
        $limit = 10;

        $provider = $this->createProvider($totalItems, $offset, $limit);
        $this->assertNull($provider->getNextLink($url));
    }

    /**
     * @test
     */
    public function getNextLink()
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
     * @return \WoohooLabs\Yin\Tests\JsonApi\Double\StubOffsetBasedPaginationProvider
     */
    private function createProvider($totalItems, $offset, $limit)
    {
        return new StubOffsetBasedPaginationProvider($totalItems, $offset, $limit);
    }
}
