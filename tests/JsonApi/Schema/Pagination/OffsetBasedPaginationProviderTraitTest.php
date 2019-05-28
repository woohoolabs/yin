<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Schema\Pagination;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\Tests\JsonApi\Double\StubOffsetBasedPaginationProvider;
use function urldecode;

class OffsetBasedPaginationProviderTraitTest extends TestCase
{
    /**
     * @test
     */
    public function getSelfLinkWhenOffsetIsNegative()
    {
        $provider = $this->createProvider(10, -6, 10);

        $link = $provider->getSelfLink("https://example.com/api/users", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getSelfLinkWhenOffsetIsZero()
    {
        $provider = $this->createProvider(10, 0, 10);

        $link = $provider->getSelfLink("https://example.com/api/users", "");

        $this->assertEquals("https://example.com/api/users?page[offset]=0&page[limit]=10", urldecode($link->getHref()));
    }

    /**
     * @test
     */
    public function getSelfLinkWhenLimitIsNegative()
    {
        $provider = $this->createProvider(10, 0, -1);

        $link = $provider->getSelfLink("https://example.com/api/users", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getSelfLinkWhenLimitIsZero()
    {
        $provider = $this->createProvider(10, 1, 0);

        $link = $provider->getSelfLink("https://example.com/api/users", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getSelfLinkWhenTotalItemsIsNegative()
    {
        $provider = $this->createProvider(-30, 1, 0);

        $link = $provider->getSelfLink("https://example.com/api/users", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getSelfLinkWhenTotalItemsIsZero()
    {
        $provider = $this->createProvider(0, 0, 10);

        $link = $provider->getSelfLink("https://example.com/api/users", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getSelfLinkWhenOffsetIsTooMuch()
    {
        $provider = $this->createProvider(30, 30, 10);

        $link = $provider->getSelfLink("https://example.com/api/users", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getSelfLinkWhenOnlyPathIsProvided()
    {
        $provider = $this->createProvider(10, 0, 10);

        $link = $provider->getSelfLink("https://example.com/api/users", "");

        $this->assertEquals("https://example.com/api/users?page[offset]=0&page[limit]=10", urldecode($link->getHref()));
    }

    /**
     * @test
     */
    public function getSelfLinkWhenPathWithQueryStringSeparatorIsProvided()
    {
        $provider = $this->createProvider(10, 0, 10);

        $link = $provider->getSelfLink("https://example.com/api/users?", "");

        $this->assertEquals("https://example.com/api/users?page[offset]=0&page[limit]=10", urldecode($link->getHref()));
    }

    /**
     * @test
     */
    public function getSelfLinkWhenPathWithQueryStringIsProvided()
    {
        $provider = $this->createProvider(10, 0, 10);

        $link = $provider->getSelfLink("https://example.com/api/users?a=b", "");

        $this->assertEquals("https://example.com/api/users?a=b&page[offset]=0&page[limit]=10", urldecode($link->getHref()));
    }

    /**
     * @test
     */
    public function getSelfLinkWhenPathAndAdditionalQueryStringIsProvided()
    {
        $provider = $this->createProvider(10, 0, 10);

        $link = $provider->getSelfLink("https://example.com/api/users?a=b", "a=c&b=d");

        $this->assertEquals("https://example.com/api/users?a=c&b=d&page[offset]=0&page[limit]=10", urldecode($link->getHref()));
    }

    /**
     * @test
     */
    public function getSelfLinkWhenPathAndAdditionalPaginationQueryStringIsProvided()
    {
        $provider = $this->createProvider(10, 0, 10);

        $link = $provider->getSelfLink("https://example.com/api/users", "page[offset]=0&page[limit]=0");

        $this->assertEquals("https://example.com/api/users?page[offset]=0&page[limit]=10", urldecode($link->getHref()));
    }

    /**
     * @test
     */
    public function getFirstLinkWhenTotalItemsIsZero()
    {
        $provider = $this->createProvider(0, 2, 10);

        $link = $provider->getFirstLink("https://example.com/api/users", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getFirstLinkWhenLimitIsZero()
    {
        $provider = $this->createProvider(10, 0, 0);

        $link = $provider->getFirstLink("https://example.com/api/users", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getFirstLink()
    {
        $provider = $this->createProvider(10, 2, 10);

        $link = $provider->getFirstLink("https://example.com/api/users", "");

        $this->assertEquals("https://example.com/api/users?page[offset]=0&page[limit]=10", urldecode($link->getHref()));
    }

    /**
     * @test
     */
    public function getLastLink()
    {
        $provider = $this->createProvider(50, 2, 10);

        $link = $provider->getLastLink("https://example.com/api/users", "");

        $this->assertEquals("https://example.com/api/users?page[offset]=40&page[limit]=10", urldecode($link->getHref()));
    }

    /**
     * @test
     */
    public function getLastLinkWhenQueryStringIsProvided()
    {
        $provider = $this->createProvider(50, 2, 10);

        $link = $provider->getLastLink("https://example.com/api/users?a=b", "");

        $this->assertEquals("https://example.com/api/users?a=b&page[offset]=40&page[limit]=10", urldecode($link->getHref()));
    }

    /**
     * @test
     */
    public function getPrevLinkWhenOffsetIsZero()
    {
        $provider = $this->createProvider(50, 0, 10);

        $link = $provider->getPrevLink("https://example.com/api/users", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getPrevLinkWhenPageIsTruncated()
    {
        $provider = $this->createProvider(50, 9, 10);

        $link = $provider->getPrevLink("https://example.com/api/users", "");

        $this->assertEquals("https://example.com/api/users?page[offset]=0&page[limit]=10", urldecode($link->getHref()));
    }

    /**
     * @test
     */
    public function getPrevLink()
    {
        $provider = $this->createProvider(50, 10, 10);

        $link = $provider->getPrevLink("https://example.com/api/users", "");

        $this->assertEquals("https://example.com/api/users?page[offset]=0&page[limit]=10", urldecode($link->getHref()));
    }

    /**
     * @test
     */
    public function getPrevLinkWhenOffsetIsMoreThanLimit()
    {
        $provider = $this->createProvider(50, 16, 10);

        $link = $provider->getPrevLink("https://example.com/api/users", "");

        $this->assertEquals("https://example.com/api/users?page[offset]=6&page[limit]=10", urldecode($link->getHref()));
    }

    /**
     * @test
     */
    public function getNextLinkWhenOffsetIsLast()
    {
        $provider = $this->createProvider(50, 41, 10);

        $link = $provider->getNextLink("https://example.com/api/users", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getNextLink()
    {
        $provider = $this->createProvider(50, 10, 10);

        $link = $provider->getNextLink("https://example.com/api/users", "");

        $this->assertEquals("https://example.com/api/users?page[offset]=20&page[limit]=10", urldecode($link->getHref()));
    }

    private function createProvider(int $totalItems, int $offset, int $limit): StubOffsetBasedPaginationProvider
    {
        return new StubOffsetBasedPaginationProvider($totalItems, $offset, $limit);
    }
}
