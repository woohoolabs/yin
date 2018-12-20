<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Schema\Pagination;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\Tests\JsonApi\Double\StubCursorBasedPaginationProvider;
use function urldecode;

class CursorBasedPaginationProviderTraitTest extends TestCase
{
    /**
     * @test
     */
    public function getSelfLinkWhenCurrentItemIsNull()
    {
        $provider = $this->createProvider(0, 0, null, 0, 0, 10);

        $link = $provider->getSelfLink("https://example.com/api/users?", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getSelfLinkWhenOnlyPathProvided()
    {
        $provider = $this->createProvider(0, 0, 2, 0, 0, 10);

        $link = $provider->getSelfLink("https://example.com/api/users", "");

        $this->assertEquals("https://example.com/api/users?page[cursor]=2&page[size]=10", urldecode($link->getHref()));
    }

    /**
     * @test
     */
    public function getSelfLinkWhenQueryStringSeparatorIsProvided()
    {
        $provider = $this->createProvider(0, 0, 2, 0, 0, 10);

        $link = $provider->getSelfLink("https://example.com/api/users?", "");

        $this->assertEquals("https://example.com/api/users?page[cursor]=2&page[size]=10", urldecode($link->getHref()));
    }

    /**
     * @test
     */
    public function getSelfLinkWhenQueryStringIsProvided()
    {
        $provider = $this->createProvider(0, 0, 2, 0, 0, 10);

        $link = $provider->getSelfLink("https://example.com/api/users?a=b", "");

        $this->assertEquals("https://example.com/api/users?a=b&page[cursor]=2&page[size]=10", urldecode($link->getHref()));
    }

    /**
     * @test
     */
    public function getSelfLinkWhenPathAndAdditionalQueryStringIsProvided()
    {
        $provider = $this->createProvider(0, 0, 2, 0, 0, 10);

        $link = $provider->getSelfLink("https://example.com/api/users?a=b", "a=c&b=d");

        $this->assertEquals("https://example.com/api/users?a=c&b=d&page[cursor]=2&page[size]=10", urldecode($link->getHref()));
    }

    /**
     * @test
     */
    public function getSelfLinkWhenPathAndAdditionalPaginationQueryStringIsProvided()
    {
        $provider = $this->createProvider(0, 0, 2, 0, 0, 10);

        $link = $provider->getSelfLink("https://example.com/api/users", "page[cursor]=0");

        $this->assertEquals("https://example.com/api/users?page[cursor]=2&page[size]=10", urldecode($link->getHref()));
    }

    /**
     * @test
     */
    public function getFirstLinkWhenFirstItemIsNull()
    {
        $provider = $this->createProvider(null, 0, 0, 0, 0, 10);

        $link = $provider->getFirstLink("https://example.com/api/users?", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getFirstLink()
    {
        $provider = $this->createProvider(0, 0, 0, 0, 0, 10);

        $link = $provider->getFirstLink("https://example.com/api/users", "");

        $this->assertEquals("https://example.com/api/users?page[cursor]=0&page[size]=10", urldecode($link->getHref()));
    }

    /**
     * @test
     */
    public function getLastLinkWhenLastItemIsNull()
    {
        $provider = $this->createProvider(0, null, 0, 0, 0, 10);

        $link = $provider->getLastLink("https://example.com/api/users", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getLastLink()
    {
        $provider = $this->createProvider(0, 4, 0, 0, 0, 10);

        $link = $provider->getLastLink("https://example.com/api/users", "");

        $this->assertEquals("https://example.com/api/users?page[cursor]=4&page[size]=10", urldecode($link->getHref()));
    }

    /**
     * @test
     */
    public function getPrevLink()
    {
        $provider = $this->createProvider(0, 0, 0, 2, 0, 10);

        $link = $provider->getPrevLink("https://example.com/api/users", "");

        $this->assertEquals("https://example.com/api/users?page[cursor]=2&page[size]=10", urldecode($link->getHref()));
    }

    /**
     * @test
     */
    public function getNextLink()
    {
        $provider = $this->createProvider(0, 0, 0, 0, 3, 10);

        $link = $provider->getNextLink("https://example.com/api/users", "");

        $this->assertEquals("https://example.com/api/users?page[cursor]=3&page[size]=10", urldecode($link->getHref()));
    }

    /**
     * @param mixed $firstItem
     * @param mixed $lastItem
     * @param mixed $currentItem
     * @param mixed $previousItem
     * @param mixed $nextItem
     */
    private function createProvider(
        $firstItem,
        $lastItem,
        $currentItem,
        $previousItem,
        $nextItem,
        int $size
    ): StubCursorBasedPaginationProvider {
        return new StubCursorBasedPaginationProvider($firstItem, $lastItem, $currentItem, $previousItem, $nextItem, $size);
    }
}
