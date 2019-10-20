<?php

declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Schema\Pagination;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\Tests\JsonApi\Double\StubFixedPageBasedPaginationProvider;

use function urldecode;

class FixedPageBasedPaginationProviderTraitTest extends TestCase
{
    /**
     * @test
     */
    public function getSelfLinkWhenPageIsNegative(): void
    {
        $provider = $this->createProvider(10, -6, 10);

        $link = $provider->getSelfLink("https://example.com/api/users", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getSelfLinkWhenPageIsZero(): void
    {
        $provider = $this->createProvider(10, 0, 10);

        $link = $provider->getSelfLink("https://example.com/api/users", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getSelfLinkWhenSizeIsNegative(): void
    {
        $provider = $this->createProvider(10, 1, -1);

        $link = $provider->getSelfLink("https://example.com/api/users", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getSelfLinkWhenSizeIsZero(): void
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
        $provider = $this->createProvider(0, 1, 10);

        $link = $provider->getSelfLink("https://example.com/api/users", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getSelfLinkWhenPageIsMoreThanLastPage(): void
    {
        $provider = $this->createProvider(30, 31, 10);

        $link = $provider->getSelfLink("https://example.com/api/users", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getSelfLinkWhenPathIsProvided(): void
    {
        $provider = $this->createProvider(10, 1, 10);

        $link = $provider->getSelfLink("https://example.com/api/users", "");
        $href = $link !== null ? $link->getHref() : "";

        $this->assertEquals("https://example.com/api/users?page[number]=1", urldecode($href));
    }

    /**
     * @test
     */
    public function getSelfLinkWhenPathWithQueryStringSeparatorIsProvided(): void
    {
        $provider = $this->createProvider(10, 1, 10);

        $link = $provider->getSelfLink("https://example.com/api/users?", "");
        $href = $link !== null ? $link->getHref() : "";

        $this->assertEquals("https://example.com/api/users?page[number]=1", urldecode($href));
    }

    /**
     * @test
     */
    public function getSelfLinkWhenPathAndAdditionalQueryStringIsProvided(): void
    {
        $provider = $this->createProvider(10, 1, 10);

        $link = $provider->getSelfLink("https://example.com/api/users?a=b", "a=c&b=d");
        $href = $link !== null ? $link->getHref() : "";

        $this->assertEquals("https://example.com/api/users?a=c&b=d&page[number]=1", urldecode($href));
    }

    /**
     * @test
     */
    public function getSelfLinkWhenPathAndAdditionalPaginationQueryStringIsProvided(): void
    {
        $provider = $this->createProvider(10, 1, 10);

        $link = $provider->getSelfLink("https://example.com/api/users", "page[number]=0&page[size]=0");
        $href = $link !== null ? $link->getHref() : "";

        $this->assertEquals("https://example.com/api/users?page[number]=1", urldecode($href));
    }

    /**
     * @test
     */
    public function getSelfLinkWhenPathWithQueryStringIsProvided(): void
    {
        $provider = $this->createProvider(10, 1, 10);

        $link = $provider->getSelfLink("https://example.com/api/users?a=b", "");
        $href = $link !== null ? $link->getHref() : "";

        $this->assertEquals("https://example.com/api/users?a=b&page[number]=1", urldecode($href));
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
    public function getFirstLinkWhenSizeIsZero(): void
    {
        $provider = $this->createProvider(10, 2, 0);

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

        $this->assertEquals("https://example.com/api/users?page[number]=1", urldecode($href));
    }

    /**
     * @test
     */
    public function getLastLinkWhenTotalItemsIsZero(): void
    {
        $provider = $this->createProvider(0, 2, 10);

        $link = $provider->getLastLink("https://example.com/api/users", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getLastLinkWhenSizeIsZero(): void
    {
        $provider = $this->createProvider(50, 2, 0);

        $link = $provider->getLastLink("https://example.com/api/users", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getLastLink(): void
    {
        $provider = $this->createProvider(50, 2, 10);

        $link = $provider->getLastLink("https://example.com/api/users", "");
        $href = $link !== null ? $link->getHref() : "";

        $this->assertEquals("https://example.com/api/users?page[number]=5", urldecode($href));
    }

    /**
     * @test
     */
    public function getPrevLinkWhenPageIsFirst(): void
    {
        $provider = $this->createProvider(50, 1, 10);

        $link = $provider->getPrevLink("https://example.com/api/users", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getPrevLinkWhenPageIsLast(): void
    {
        $provider = $this->createProvider(50, 5, 10);

        $link = $provider->getPrevLink("https://example.com/api/users", "");
        $href = $link !== null ? $link->getHref() : "";

        $this->assertEquals("https://example.com/api/users?page[number]=4", urldecode($href));
    }

    /**
     * @test
     */
    public function getPrevLink(): void
    {
        $provider = $this->createProvider(50, 2, 10);

        $link = $provider->getPrevLink("https://example.com/api/users", "");
        $href = $link !== null ? $link->getHref() : "";

        $this->assertEquals("https://example.com/api/users?page[number]=1", urldecode($href));
    }

    /**
     * @test
     */
    public function getNextLinkWhenPageIsLast(): void
    {
        $provider = $this->createProvider(50, 5, 10);

        $link = $provider->getNextLink("https://example.com/api/users", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getNextLinkWhenPageIsBeforeLast(): void
    {
        $provider = $this->createProvider(50, 4, 10);

        $link = $provider->getNextLink("https://example.com/api/users", "");
        $href = $link !== null ? $link->getHref() : "";

        $this->assertEquals("https://example.com/api/users?page[number]=5", urldecode($href));
    }

    /**
     * @test
     */
    public function getNextLink(): void
    {
        $provider = $this->createProvider(50, 2, 10);

        $link = $provider->getNextLink("https://example.com/api/users?", "");
        $href = $link !== null ? $link->getHref() : "";

        $this->assertEquals("https://example.com/api/users?page[number]=3", urldecode($href));
    }

    private function createProvider(int $totalItems, int $page, int $size): StubFixedPageBasedPaginationProvider
    {
        return new StubFixedPageBasedPaginationProvider($totalItems, $page, $size);
    }
}
