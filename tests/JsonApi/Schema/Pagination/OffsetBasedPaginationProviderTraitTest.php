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
    public function getSelfLinkWhenOffsetIsNegative(): void
    {
        $provider = $this->createProvider(10, -6, 10);

        $link = $provider->getSelfLink("https://example.com/api/users", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getSelfLinkWhenOffsetIsZero(): void
    {
        $provider = $this->createProvider(10, 0, 10);

        $link = $provider->getSelfLink("https://example.com/api/users", "");
        $href = $link !== null ? $link->getHref() : "";

        $this->assertEquals("https://example.com/api/users?page%5Boffset%5D=0&page%5Blimit%5D=10", $href);
    }

    /**
     * @test
     */
    public function getSelfLinkWhenLimitIsNegative(): void
    {
        $provider = $this->createProvider(10, 0, -1);

        $link = $provider->getSelfLink("https://example.com/api/users", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getSelfLinkWhenLimitIsZero(): void
    {
        $provider = $this->createProvider(10, 1, 0);

        $link = $provider->getSelfLink("https://example.com/api/users", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getSelfLinkWhenTotalItemsIsNegative(): void
    {
        $provider = $this->createProvider(-30, 1, 0);

        $link = $provider->getSelfLink("https://example.com/api/users", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getSelfLinkWhenTotalItemsIsZero(): void
    {
        $provider = $this->createProvider(0, 0, 10);

        $link = $provider->getSelfLink("https://example.com/api/users", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getSelfLinkWhenOffsetIsTooMuch(): void
    {
        $provider = $this->createProvider(30, 30, 10);

        $link = $provider->getSelfLink("https://example.com/api/users", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getSelfLinkWhenOnlyPathIsProvided(): void
    {
        $provider = $this->createProvider(10, 0, 10);

        $link = $provider->getSelfLink("https://example.com/api/users", "");
        $href = $link !== null ? $link->getHref() : "";

        $this->assertEquals("https://example.com/api/users?page%5Boffset%5D=0&page%5Blimit%5D=10", $href);
    }

    /**
     * @test
     */
    public function getSelfLinkWhenPathWithQueryStringSeparatorIsProvided(): void
    {
        $provider = $this->createProvider(10, 0, 10);

        $link = $provider->getSelfLink("https://example.com/api/users?", "");
        $href = $link !== null ? $link->getHref() : "";

        $this->assertEquals("https://example.com/api/users?page%5Boffset%5D=0&page%5Blimit%5D=10", $href);
    }

    /**
     * @test
     */
    public function getSelfLinkWhenPathWithQueryStringIsProvided(): void
    {
        $provider = $this->createProvider(10, 0, 10);

        $link = $provider->getSelfLink("https://example.com/api/users?a=b", "");
        $href = $link !== null ? $link->getHref() : "";

        $this->assertEquals("https://example.com/api/users?a=b&page%5Boffset%5D=0&page%5Blimit%5D=10", $href);
    }

    /**
     * @test
     */
    public function getSelfLinkWhenPathAndAdditionalQueryStringIsProvided(): void
    {
        $provider = $this->createProvider(10, 0, 10);

        $link = $provider->getSelfLink("https://example.com/api/users?a=b", "a=c&b=d");
        $href = $link !== null ? $link->getHref() : "";

        $this->assertEquals("https://example.com/api/users?a=c&b=d&page%5Boffset%5D=0&page%5Blimit%5D=10", $href);
    }

    /**
     * @test
     */
    public function getSelfLinkWhenPathAndAdditionalPaginationQueryStringIsProvided(): void
    {
        $provider = $this->createProvider(10, 0, 10);

        $link = $provider->getSelfLink("https://example.com/api/users", "page[offset]=0&page[limit]=0");
        $href = $link !== null ? $link->getHref() : "";

        $this->assertEquals("https://example.com/api/users?page%5Boffset%5D=0&page%5Blimit%5D=10", $href);
    }

    /**
     * @test
     */
    public function getFirstLinkWhenTotalItemsIsZero(): void
    {
        $provider = $this->createProvider(0, 2, 10);

        $link = $provider->getFirstLink("https://example.com/api/users", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getFirstLinkWhenLimitIsZero(): void
    {
        $provider = $this->createProvider(10, 0, 0);

        $link = $provider->getFirstLink("https://example.com/api/users", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getFirstLink(): void
    {
        $provider = $this->createProvider(10, 2, 10);

        $link = $provider->getFirstLink("https://example.com/api/users", "");
        $href = $link !== null ? $link->getHref() : "";

        $this->assertEquals("https://example.com/api/users?page%5Boffset%5D=0&page%5Blimit%5D=10", $href);
    }

    /**
     * @test
     */
    public function getLastLink(): void
    {
        $provider = $this->createProvider(50, 2, 10);

        $link = $provider->getLastLink("https://example.com/api/users", "");
        $href = $link !== null ? $link->getHref() : "";

        $this->assertEquals("https://example.com/api/users?page%5Boffset%5D=40&page%5Blimit%5D=10", $href);
    }

    /**
     * @test
     */
    public function getLastLinkWhenQueryStringIsProvided(): void
    {
        $provider = $this->createProvider(50, 2, 10);

        $link = $provider->getLastLink("https://example.com/api/users?a=b", "");
        $href = $link !== null ? $link->getHref() : "";

        $this->assertEquals("https://example.com/api/users?a=b&page%5Boffset%5D=40&page%5Blimit%5D=10", $href);
    }

    /**
     * @test
     */
    public function getPrevLinkWhenOffsetIsZero(): void
    {
        $provider = $this->createProvider(50, 0, 10);

        $link = $provider->getPrevLink("https://example.com/api/users", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getPrevLinkWhenPageIsTruncated(): void
    {
        $provider = $this->createProvider(50, 9, 10);

        $link = $provider->getPrevLink("https://example.com/api/users", "");
        $href = $link !== null ? $link->getHref() : "";

        $this->assertEquals("https://example.com/api/users?page%5Boffset%5D=0&page%5Blimit%5D=10", $href);
    }

    /**
     * @test
     */
    public function getPrevLink(): void
    {
        $provider = $this->createProvider(50, 10, 10);

        $link = $provider->getPrevLink("https://example.com/api/users", "");
        $href = $link !== null ? $link->getHref() : "";

        $this->assertEquals("https://example.com/api/users?page%5Boffset%5D=0&page%5Blimit%5D=10", $href);
    }

    /**
     * @test
     */
    public function getPrevLinkWhenOffsetIsMoreThanLimit(): void
    {
        $provider = $this->createProvider(50, 16, 10);

        $link = $provider->getPrevLink("https://example.com/api/users", "");
        $href = $link !== null ? $link->getHref() : "";

        $this->assertEquals("https://example.com/api/users?page%5Boffset%5D=6&page%5Blimit%5D=10", $href);
    }

    /**
     * @test
     */
    public function getNextLinkWhenOffsetIsLast(): void
    {
        $provider = $this->createProvider(50, 41, 10);

        $link = $provider->getNextLink("https://example.com/api/users", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getNextLink(): void
    {
        $provider = $this->createProvider(50, 10, 10);

        $link = $provider->getNextLink("https://example.com/api/users", "");
        $href = $link !== null ? $link->getHref() : "";

        $this->assertEquals("https://example.com/api/users?page%5Boffset%5D=20&page%5Blimit%5D=10", $href);
    }

    private function createProvider(int $totalItems, int $offset, int $limit): StubOffsetBasedPaginationProvider
    {
        return new StubOffsetBasedPaginationProvider($totalItems, $offset, $limit);
    }
}
