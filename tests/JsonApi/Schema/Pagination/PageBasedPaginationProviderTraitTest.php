<?php
namespace WoohooLabsTest\Yin\JsonApi\Schema\Pagination;

use PHPUnit_Framework_TestCase;
use WoohooLabsTest\Yin\JsonApi\Utils\StubPageBasedPaginationProvider;

class PageBasedPaginationProviderTraitTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function getSelfLinkWhenPageIsNegative()
    {
        $url = "http://example.com/api/users";
        $totalItems = 10;
        $page = -6;
        $size = 10;

        $provider = $this->createProvider($totalItems, $page, $size);
        $this->assertNull($provider->getSelfLink($url));
    }

    /**
     * @test
     */
    public function getSelfLinkWhenPageIsZero()
    {
        $url = "http://example.com/api/users";
        $totalItems = 10;
        $page = 0;
        $size = 10;

        $provider = $this->createProvider($totalItems, $page, $size);
        $this->assertNull($provider->getSelfLink($url));
    }

    /**
     * @test
     */
    public function getSelfLinkWhenSizeIsNegative()
    {
        $url = "http://example.com/api/users";
        $totalItems = 10;
        $page = 1;
        $size = -1;

        $provider = $this->createProvider($totalItems, $page, $size);
        $this->assertNull($provider->getSelfLink($url));
    }

    /**
     * @test
     */
    public function getSelfLinkWhenSizeIsZero()
    {
        $url = "http://example.com/api/users";
        $totalItems = 10;
        $page = 1;
        $size = 0;

        $provider = $this->createProvider($totalItems, $page, $size);
        $this->assertNull($provider->getSelfLink($url));
    }

    /**
     * @test
     */
    public function getSelfLinkWhenTotalItemsIsNegative()
    {
        $url = "http://example.com/api/users";
        $totalItems = -30;
        $page = 1;
        $size = 0;

        $provider = $this->createProvider($totalItems, $page, $size);
        $this->assertNull($provider->getSelfLink($url));
    }

    /**
     * @test
     */
    public function getSelfLinkWhenTotalItemsIsZero()
    {
        $url = "http://example.com/api/users";
        $totalItems = 0;
        $page = 1;
        $size = 10;

        $provider = $this->createProvider($totalItems, $page, $size);
        $this->assertNull($provider->getSelfLink($url));
    }

    /**
     * @test
     */
    public function getSelfLinkWhenPageIsMoreThanLastPage()
    {
        $url = "http://example.com/api/users";
        $totalItems = 30;
        $page = 31;
        $size = 10;

        $provider = $this->createProvider($totalItems, $page, $size);
        $this->assertNull($provider->getSelfLink($url));
    }

    /**
     * @test
     */
    public function getSelfLinkWhenPathIsProvided()
    {
        $url = "http://example.com/api/users";
        $totalItems = 10;
        $page = 1;
        $size = 10;

        $provider = $this->createProvider($totalItems, $page, $size);
        $this->assertEquals("$url?page[number]=$page&page[size]=$size", $provider->getSelfLink($url)->getHref());
    }

    /**
     * @test
     */
    public function getSelfLinkWhenPathWithQueryStringSeparatorIsProvided()
    {
        $url = "http://example.com/api/users?";
        $totalItems = 10;
        $page = 1;
        $size = 10;

        $provider = $this->createProvider($totalItems, $page, $size);
        $this->assertEquals("{$url}page[number]=$page&page[size]=$size", $provider->getSelfLink($url)->getHref());
    }

    /**
     * @test
     */
    public function getSelfLinkWhenPathWithQueryStringIsProvided()
    {
        $url = "http://example.com/api/users?a=b";
        $totalItems = 10;
        $page = 1;
        $size = 10;

        $provider = $this->createProvider($totalItems, $page, $size);
        $this->assertEquals("{$url}&page[number]=$page&page[size]=$size", $provider->getSelfLink($url)->getHref());
    }

    /**
     * @test
     */
    public function getFirstLinkWhenTotalItemsIsZero()
    {
        $url = "http://example.com/api/users";
        $totalItems = 0;
        $page = 2;
        $size = 10;

        $provider = $this->createProvider($totalItems, $page, $size);
        $this->assertNull($provider->getFirstLink($url));
    }

    /**
     * @test
     */
    public function getFirstLinkWhenSizeIsZero()
    {
        $url = "http://example.com/api/users";
        $totalItems = 10;
        $page = 2;
        $size = 0;

        $provider = $this->createProvider($totalItems, $page, $size);
        $this->assertNull($provider->getFirstLink($url));
    }

    /**
     * @test
     */
    public function getFirstLink()
    {
        $url = "http://example.com/api/users";
        $totalItems = 10;
        $page = 2;
        $size = 10;

        $provider = $this->createProvider($totalItems, $page, $size);
        $this->assertEquals("$url?page[number]=1&page[size]=$size", $provider->getFirstLink($url)->getHref());
    }

    /**
     * @test
     */
    public function getLastLinkWhenTotalItemsIsZero()
    {
        $url = "http://example.com/api/users";
        $totalItems = 0;
        $page = 2;
        $size = 10;

        $provider = $this->createProvider($totalItems, $page, $size);
        $this->assertNull($provider->getLastLink($url));
    }

    /**
     * @test
     */
    public function getLastLinkWhenSizeIsZero()
    {
        $url = "http://example.com/api/users";
        $totalItems = 50;
        $page = 2;
        $size = 0;

        $provider = $this->createProvider($totalItems, $page, $size);
        $this->assertNull($provider->getLastLink($url));
    }

    /**
     * @test
     */
    public function getLastLink()
    {
        $url = "http://example.com/api/users";
        $totalItems = 50;
        $page = 2;
        $size = 10;

        $provider = $this->createProvider($totalItems, $page, $size);
        $this->assertEquals("$url?page[number]=5&page[size]=$size", $provider->getLastLink($url)->getHref());
    }

    /**
     * @test
     */
    public function getPrevLinkWhenPageIsFirst()
    {
        $url = "http://example.com/api/users";
        $totalItems = 50;
        $page = 1;
        $size = 10;

        $provider = $this->createProvider($totalItems, $page, $size);
        $this->assertNull($provider->getPrevLink($url));
    }

    /**
     * @test
     */
    public function getPrevLink()
    {
        $url = "http://example.com/api/users";
        $totalItems = 50;
        $page = 2;
        $size = 10;

        $provider = $this->createProvider($totalItems, $page, $size);
        $this->assertEquals("$url?page[number]=1&page[size]=$size", $provider->getPrevLink($url)->getHref());
    }

    /**
     * @test
     */
    public function getNextLinkWhenPageIsLast()
    {
        $url = "http://example.com/api/users";
        $totalItems = 50;
        $page = 5;
        $size = 10;

        $provider = $this->createProvider($totalItems, $page, $size);
        $this->assertNull($provider->getNextLink($url));
    }

    /**
     * @test
     */
    public function getNextLink()
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
