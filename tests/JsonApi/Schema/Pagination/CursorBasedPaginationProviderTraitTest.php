<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Schema\Pagination;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\Tests\JsonApi\Double\StubCursorBasedPaginationProvider;

class CursorBasedPaginationProviderTraitTest extends TestCase
{
    /**
     * @test
     */
    public function getSelfLinkWhenCurrentItemIsNull()
    {
        $provider = $this->createProvider(0, 0, null, 0, 0);

        $link = $provider->getSelfLink("https://example.com/api/users?", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getSelfLinkWhenOnlyPathProvided()
    {
        $provider = $this->createProvider(0, 0, 2, 0, 0);

        $link = $provider->getSelfLink("https://example.com/api/users", "");

        $this->assertEquals("https://example.com/api/users?page[cursor]=2", urldecode($link->getHref()));
    }

    /**
     * @test
     */
    public function getSelfLinkWhenQueryStringSeparatorIsProvided()
    {
        $provider = $this->createProvider(0, 0, 2, 0, 0);

        $link = $provider->getSelfLink("http://example.com/api/users?", "");

        $this->assertEquals("http://example.com/api/users?page[cursor]=2", urldecode($link->getHref()));
    }

    /**
     * @test
     */
    public function getSelfLinkWhenQueryStringIsProvided()
    {
        $provider = $this->createProvider(0, 0, 2, 0, 0);

        $link = $provider->getSelfLink("http://example.com/api/users?a=b", "");

        $this->assertEquals("http://example.com/api/users?a=b&page[cursor]=2", urldecode($link->getHref()));
    }

    /**
     * @test
     */
    public function getSelfLinkWhenPathAndAdditionalQueryStringIsProvided()
    {
        $provider = $this->createProvider(0, 0, 2, 0, 0);

        $link = $provider->getSelfLink("http://example.com/api/users?a=b", "a=c&b=d");

        $this->assertEquals("http://example.com/api/users?a=c&b=d&page[cursor]=2", urldecode($link->getHref()));
    }

    /**
     * @test
     */
    public function getSelfLinkWhenPathAndAdditionalPaginationQueryStringIsProvided()
    {
        $provider = $this->createProvider(0, 0, 2, 0, 0);

        $link = $provider->getSelfLink("http://example.com/api/users", "page[cursor]=0");

        $this->assertEquals("http://example.com/api/users?page[cursor]=2", urldecode($link->getHref()));
    }

    /**
     * @test
     */
    public function getFirstLinkWhenFirstItemIsNull()
    {
        $provider = $this->createProvider(null, 0, 0, 0, 0);

        $link = $provider->getFirstLink("http://example.com/api/users?", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getFirstLink()
    {
        $provider = $this->createProvider(0, 0, 0, 0, 0);

        $link = $provider->getFirstLink("http://example.com/api/users", "");

        $this->assertEquals("http://example.com/api/users?page[cursor]=0", urldecode($link->getHref()));
    }

    /**
     * @test
     */
    public function getLastLinkWhenLastItemIsNull()
    {
        $provider = $this->createProvider(0, null, 0, 0, 0);

        $link = $provider->getLastLink("http://example.com/api/users", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getLastLink()
    {
        $provider = $this->createProvider(0, 4, 0, 0, 0);

        $link = $provider->getLastLink("http://example.com/api/users", "");

        $this->assertEquals("http://example.com/api/users?page[cursor]=4", urldecode($link->getHref()));
    }

    /**
     * @test
     */
    public function getPrevLink()
    {
        $provider = $this->createProvider(0, 0, 0, 2, 0);

        $link = $provider->getPrevLink("http://example.com/api/users", "");

        $this->assertEquals("http://example.com/api/users?page[cursor]=2", urldecode($link->getHref()));
    }

    /**
     * @test
     */
    public function getNextLink()
    {
        $provider = $this->createProvider(0, 0, 0, 0, 3);

        $link = $provider->getNextLink("http://example.com/api/users", "");

        $this->assertEquals("http://example.com/api/users?page[cursor]=3", urldecode($link->getHref()));
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
        $nextItem
    ): StubCursorBasedPaginationProvider {
        return new StubCursorBasedPaginationProvider($firstItem, $lastItem, $currentItem, $previousItem, $nextItem);
    }
}
