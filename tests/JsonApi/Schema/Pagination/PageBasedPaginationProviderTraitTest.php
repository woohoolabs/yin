<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Schema\Pagination;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\Tests\JsonApi\Double\StubPageBasedPaginationProvider;

class PageBasedPaginationProviderTraitTest extends TestCase
{
    /**
     * @test
     */
    public function getSelfLinkWhenPageIsNegative()
    {
        $provider = $this->createProvider(10, -6, 10);

        $link = $provider->getSelfLink("http://example.com/api/users", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getSelfLinkWhenPageIsZero()
    {
        $provider = $this->createProvider(10, 0, 10);

        $link = $provider->getSelfLink("http://example.com/api/users", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getSelfLinkWhenSizeIsNegative()
    {
        $provider = $this->createProvider(10, 1, -1);

        $link = $provider->getSelfLink("http://example.com/api/users", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getSelfLinkWhenSizeIsZero()
    {
        $provider = $this->createProvider(10, 1, 0);

        $link = $provider->getSelfLink("http://example.com/api/users", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getSelfLinkWhenTotalItemsIsNegative()
    {
        $provider = $this->createProvider(-30, 1, 0);

        $link = $provider->getSelfLink("http://example.com/api/users", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getSelfLinkWhenTotalItemsIsZero()
    {
        $provider = $this->createProvider(0, 1, 10);

        $link = $provider->getSelfLink("http://example.com/api/users", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getSelfLinkWhenPageIsMoreThanLastPage()
    {
        $provider = $this->createProvider(30, 31, 10);

        $link = $provider->getSelfLink("http://example.com/api/users", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getSelfLinkWhenPathIsProvided()
    {
        $provider = $this->createProvider(10, 1, 10);

        $link = $provider->getSelfLink("http://example.com/api/users", "");

        $this->assertEquals("http://example.com/api/users?page[number]=1&page[size]=10", urldecode($link->getHref()));
    }

    /**
     * @test
     */
    public function getSelfLinkWhenPathWithQueryStringSeparatorIsProvided()
    {
        $provider = $this->createProvider(10, 1, 10);

        $link = $provider->getSelfLink("http://example.com/api/users?", "");

        $this->assertEquals("http://example.com/api/users?page[number]=1&page[size]=10", urldecode($link->getHref()));
    }

    /**
     * @test
     */
    public function getSelfLinkWhenPathAndAdditionalQueryStringIsProvided()
    {
        $provider = $this->createProvider(10, 1, 10);

        $link = $provider->getSelfLink("http://example.com/api/users?a=b", "a=c&b=d");

        $this->assertEquals("http://example.com/api/users?a=c&b=d&page[number]=1&page[size]=10", urldecode($link->getHref()));
    }

    /**
     * @test
     */
    public function getSelfLinkWhenPathAndAdditionalPaginationQueryStringIsProvided()
    {
        $provider = $this->createProvider(10, 1, 10);

        $link = $provider->getSelfLink("http://example.com/api/users", "page[number]=0&page[size]=0");

        $this->assertEquals("http://example.com/api/users?page[number]=1&page[size]=10", urldecode($link->getHref()));
    }

    /**
     * @test
     */
    public function getSelfLinkWhenPathWithQueryStringIsProvided()
    {
        $provider = $this->createProvider(10, 1, 10);

        $link = $provider->getSelfLink("http://example.com/api/users?a=b", "");

        $this->assertEquals("http://example.com/api/users?a=b&page[number]=1&page[size]=10", urldecode($link->getHref()));
    }

    /**
     * @test
     */
    public function getFirstLinkWhenTotalItemsIsZero()
    {
        $provider = $this->createProvider(0, 2, 10);

        $link = $provider->getFirstLink("http://example.com/api/users", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getFirstLinkWhenSizeIsZero()
    {
        $provider = $this->createProvider(10, 2, 0);

        $link = $provider->getFirstLink("http://example.com/api/users", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getFirstLink()
    {
        $provider = $this->createProvider(10, 2, 10);

        $link = $provider->getFirstLink("http://example.com/api/users", "");

        $this->assertEquals("http://example.com/api/users?page[number]=1&page[size]=10", urldecode($link->getHref()));
    }

    /**
     * @test
     */
    public function getLastLinkWhenTotalItemsIsZero()
    {
        $provider = $this->createProvider(0, 2, 10);

        $link = $provider->getLastLink("http://example.com/api/users", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getLastLinkWhenSizeIsZero()
    {
        $provider = $this->createProvider(50, 2, 0);

        $link = $provider->getLastLink("http://example.com/api/users", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getLastLink()
    {
        $provider = $this->createProvider(50, 2, 10);

        $link = $provider->getLastLink("http://example.com/api/users", "");

        $this->assertEquals("http://example.com/api/users?page[number]=5&page[size]=10", urldecode($link->getHref()));
    }

    /**
     * @test
     */
    public function getPrevLinkWhenPageIsFirst()
    {
        $provider = $this->createProvider(50, 1, 10);

        $link = $provider->getPrevLink("http://example.com/api/users", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getPrevLinkWhenPageIsLast()
    {
        $provider = $this->createProvider(50, 5, 10);

        $link = $provider->getPrevLink("http://example.com/api/users", "");

        $this->assertEquals("http://example.com/api/users?page[number]=4&page[size]=10", urldecode($link->getHref()));
    }

    /**
     * @test
     */
    public function getPrevLink()
    {
        $provider = $this->createProvider(50, 2, 10);

        $link = $provider->getPrevLink("http://example.com/api/users", "");

        $this->assertEquals("http://example.com/api/users?page[number]=1&page[size]=10", urldecode($link->getHref()));
    }

    /**
     * @test
     */
    public function getNextLinkWhenPageIsLast()
    {
        $provider = $this->createProvider(50, 5, 10);

        $link = $provider->getNextLink("http://example.com/api/users", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getNextLinkWhenPageIsBeforeLast()
    {
        $provider = $this->createProvider(50, 4, 10);

        $link = $provider->getNextLink("http://example.com/api/users", "");

        $this->assertEquals("http://example.com/api/users?page[number]=5&page[size]=10", urldecode($link->getHref()));
    }

    /**
     * @test
     */
    public function getNextLink()
    {
        $provider = $this->createProvider(50, 2, 10);

        $link = $provider->getNextLink("http://example.com/api/users?", "");

        $this->assertEquals("http://example.com/api/users?page[number]=3&page[size]=10", urldecode($link->getHref()));
    }

    private function createProvider(int $totalItems, int $page, int $size): StubPageBasedPaginationProvider
    {
        return new StubPageBasedPaginationProvider($totalItems, $page, $size);
    }
}
