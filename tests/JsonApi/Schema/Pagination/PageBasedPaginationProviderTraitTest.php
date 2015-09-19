<?php
namespace WoohooLabsTest\Yin\JsonApi\Schema\Pagination;

use PHPUnit_Framework_TestCase;
use WoohooLabsTest\Yin\JsonApi\Utils\StubPageBasedPaginationProvider;

class PageBasedPaginationProviderTraitTest extends PHPUnit_Framework_TestCase
{
    public function testGetSelfLinkWhenPageIsNegative()
    {
        $url = "http://example.com/api/users";
        $totalItems = 10;
        $page = -6;
        $size = 10;

        $provider = $this->createProvider($totalItems, $page, $size);
        $this->assertNull($provider->getSelfLink($url));
    }

    public function testGetSelfLinkWhenPageIsZero()
    {
        $url = "http://example.com/api/users";
        $totalItems = 10;
        $page = 0;
        $size = 10;

        $provider = $this->createProvider($totalItems, $page, $size);
        $this->assertNull($provider->getSelfLink($url));
    }

    public function testGetSelfLinkWhenSizeIsNegative()
    {
        $url = "http://example.com/api/users";
        $totalItems = 10;
        $page = 1;
        $size = -1;

        $provider = $this->createProvider($totalItems, $page, $size);
        $this->assertNull($provider->getSelfLink($url));
    }

    public function testGetSelfLinkWhenSizeIsZero()
    {
        $url = "http://example.com/api/users";
        $totalItems = 10;
        $page = 1;
        $size = 0;

        $provider = $this->createProvider($totalItems, $page, $size);
        $this->assertNull($provider->getSelfLink($url));
    }

    public function testGetSelfLinkWhenTotalItemsIsNegative()
    {
        $url = "http://example.com/api/users";
        $totalItems = -30;
        $page = 1;
        $size = 0;

        $provider = $this->createProvider($totalItems, $page, $size);
        $this->assertNull($provider->getSelfLink($url));
    }

    public function testGetSelfLinkWhenTotalItemsIsZero()
    {
        $url = "http://example.com/api/users";
        $totalItems = 0;
        $page = 1;
        $size = 10;

        $provider = $this->createProvider($totalItems, $page, $size);
        $this->assertNull($provider->getSelfLink($url));
    }

    public function testGetSelfLinkWhenPageIsMoreThanLastPage()
    {
        $url = "http://example.com/api/users";
        $totalItems = 30;
        $page = 31;
        $size = 10;

        $provider = $this->createProvider($totalItems, $page, $size);
        $this->assertNull($provider->getSelfLink($url));
    }

    public function testGetSelfLinkWhenPathIsProvided()
    {
        $url = "http://example.com/api/users";
        $totalItems = 10;
        $page = 1;
        $size = 10;

        $provider = $this->createProvider($totalItems, $page, $size);
        $this->assertEquals("$url?page[number]=$page&page[size]=$size", $provider->getSelfLink($url)->getHref());
    }

    public function testGetSelfLinkWhenPathWithQueryStringSeparatorIsProvided()
    {
        $url = "http://example.com/api/users?";
        $totalItems = 10;
        $page = 1;
        $size = 10;

        $provider = $this->createProvider($totalItems, $page, $size);
        $this->assertEquals("{$url}page[number]=$page&page[size]=$size", $provider->getSelfLink($url)->getHref());
    }

    public function testGetSelfLinkWhenPathWithQueryStringIsProvided()
    {
        $url = "http://example.com/api/users?a=b";
        $totalItems = 10;
        $page = 1;
        $size = 10;

        $provider = $this->createProvider($totalItems, $page, $size);
        $this->assertEquals("{$url}&page[number]=$page&page[size]=$size", $provider->getSelfLink($url)->getHref());
    }

    public function testGetFirstLinkWhenTotalItemsIsZero()
    {
        $url = "http://example.com/api/users";
        $totalItems = 0;
        $page = 2;
        $size = 10;

        $provider = $this->createProvider($totalItems, $page, $size);
        $this->assertNull($provider->getFirstLink($url));
    }

    public function testGetFirstLinkWhenSizeIsZero()
    {
        $url = "http://example.com/api/users";
        $totalItems = 10;
        $page = 2;
        $size = 0;

        $provider = $this->createProvider($totalItems, $page, $size);
        $this->assertNull($provider->getFirstLink($url));
    }

    public function testGetFirstLink()
    {
        $url = "http://example.com/api/users";
        $totalItems = 10;
        $page = 2;
        $size = 10;

        $provider = $this->createProvider($totalItems, $page, $size);
        $this->assertEquals("$url?page[number]=1&page[size]=$size", $provider->getFirstLink($url)->getHref());
    }

    public function testGetLastLinkWhenTotalItemsIsZero()
    {
        $url = "http://example.com/api/users";
        $totalItems = 0;
        $page = 2;
        $size = 10;

        $provider = $this->createProvider($totalItems, $page, $size);
        $this->assertNull($provider->getLastLink($url));
    }

    public function testGetLastLinkWhenSizeIsZero()
    {
        $url = "http://example.com/api/users";
        $totalItems = 50;
        $page = 2;
        $size = 0;

        $provider = $this->createProvider($totalItems, $page, $size);
        $this->assertNull($provider->getLastLink($url));
    }

    public function testGetLastLink()
    {
        $url = "http://example.com/api/users";
        $totalItems = 50;
        $page = 2;
        $size = 10;

        $provider = $this->createProvider($totalItems, $page, $size);
        $this->assertEquals("$url?page[number]=5&page[size]=$size", $provider->getLastLink($url)->getHref());
    }

    public function testGetPrevLinkWhenPageIsFirst()
    {
        $url = "http://example.com/api/users";
        $totalItems = 50;
        $page = 1;
        $size = 10;

        $provider = $this->createProvider($totalItems, $page, $size);
        $this->assertNull($provider->getPrevLink($url));
    }

    public function testGetPrevLink()
    {
        $url = "http://example.com/api/users";
        $totalItems = 50;
        $page = 2;
        $size = 10;

        $provider = $this->createProvider($totalItems, $page, $size);
        $this->assertEquals("$url?page[number]=1&page[size]=$size", $provider->getPrevLink($url)->getHref());
    }

    public function testGetNextLinkWhenPageIsLast()
    {
        $url = "http://example.com/api/users";
        $totalItems = 50;
        $page = 5;
        $size = 10;

        $provider = $this->createProvider($totalItems, $page, $size);
        $this->assertNull($provider->getNextLink($url));
    }

    public function testGetNextLink()
    {
        $url = "http://example.com/api/users";
        $totalItems = 50;
        $page = 2;
        $size = 10;

        $provider = $this->createProvider($totalItems, $page, $size);
        $this->assertEquals("$url?page[number]=3&page[size]=$size", $provider->getNextLink($url)->getHref());
    }

    /**
     * @param int $totalItems
     * @param int $page
     * @param int $size
     * @return \WoohooLabsTest\Yin\JsonApi\Utils\StubPageBasedPaginationProvider
     */
    private function createProvider($totalItems, $page, $size)
    {
        return new StubPageBasedPaginationProvider($totalItems, $page, $size);
    }
}
