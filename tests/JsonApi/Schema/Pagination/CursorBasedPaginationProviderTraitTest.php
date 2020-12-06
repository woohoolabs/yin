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
    public function getSelfLinkWhenCurrentItemIsNull(): void
    {
        $provider = $this->createProvider(0, 0, null, 0, 0, 10);

        $link = $provider->getSelfLink("https://example.com/api/users?", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getSelfLinkWhenOnlyPathProvided(): void
    {
        $provider = $this->createProvider(0, 0, 2, 0, 0, 10);

        $link = $provider->getSelfLink("https://example.com/api/users", "");
        $href = $link !== null ? $link->getHref() : "";

        $this->assertEquals("https://example.com/api/users?page%5Bcursor%5D=2&page%5Bsize%5D=10", $href);
    }

    /**
     * @test
     */
    public function getSelfLinkWhenQueryStringSeparatorIsProvided(): void
    {
        $provider = $this->createProvider(0, 0, 2, 0, 0, 10);

        $link = $provider->getSelfLink("https://example.com/api/users?", "");
        $href = $link !== null ? $link->getHref() : "";

        $this->assertEquals("https://example.com/api/users?page%5Bcursor%5D=2&page%5Bsize%5D=10", $href);
    }

    /**
     * @test
     */
    public function getSelfLinkWhenQueryStringIsProvided(): void
    {
        $provider = $this->createProvider(0, 0, 2, 0, 0, 10);

        $link = $provider->getSelfLink("https://example.com/api/users?a=b", "");
        $href = $link !== null ? $link->getHref() : "";

        $this->assertEquals("https://example.com/api/users?a=b&page%5Bcursor%5D=2&page%5Bsize%5D=10", $href);
    }

    /**
     * @test
     */
    public function getSelfLinkWhenPathAndAdditionalQueryStringIsProvided(): void
    {
        $provider = $this->createProvider(0, 0, 2, 0, 0, 10);

        $link = $provider->getSelfLink("https://example.com/api/users?a=b", "a=c&b=d");
        $href = $link !== null ? $link->getHref() : "";

        $this->assertEquals("https://example.com/api/users?a=c&b=d&page%5Bcursor%5D=2&page%5Bsize%5D=10", $href);
    }

    /**
     * @test
     */
    public function getSelfLinkWhenPathAndAdditionalPaginationQueryStringIsProvided(): void
    {
        $provider = $this->createProvider(0, 0, 2, 0, 0, 10);

        $link = $provider->getSelfLink("https://example.com/api/users", "page[cursor]=0");
        $href = $link !== null ? $link->getHref() : "";

        $this->assertEquals("https://example.com/api/users?page%5Bcursor%5D=2&page%5Bsize%5D=10", $href);
    }

    /**
     * @test
     */
    public function getFirstLinkWhenFirstItemIsNull(): void
    {
        $provider = $this->createProvider(null, 0, 0, 0, 0, 10);

        $link = $provider->getFirstLink("https://example.com/api/users?", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getFirstLink(): void
    {
        $provider = $this->createProvider(0, 0, 0, 0, 0, 10);

        $link = $provider->getFirstLink("https://example.com/api/users", "");
        $href = $link !== null ? $link->getHref() : "";

        $this->assertEquals("https://example.com/api/users?page%5Bcursor%5D=0&page%5Bsize%5D=10", $href);
    }

    /**
     * @test
     */
    public function getLastLinkWhenLastItemIsNull(): void
    {
        $provider = $this->createProvider(0, null, 0, 0, 0, 10);

        $link = $provider->getLastLink("https://example.com/api/users", "");

        $this->assertNull($link);
    }

    /**
     * @test
     */
    public function getLastLink(): void
    {
        $provider = $this->createProvider(0, 4, 0, 0, 0, 10);

        $link = $provider->getLastLink("https://example.com/api/users", "");
        $href = $link !== null ? $link->getHref() : "";

        $this->assertEquals("https://example.com/api/users?page%5Bcursor%5D=4&page%5Bsize%5D=10", $href);
    }

    /**
     * @test
     */
    public function getPrevLink(): void
    {
        $provider = $this->createProvider(0, 0, 0, 2, 0, 10);

        $link = $provider->getPrevLink("https://example.com/api/users", "");
        $href = $link !== null ? $link->getHref() : "";

        $this->assertEquals("https://example.com/api/users?page%5Bcursor%5D=2&page%5Bsize%5D=10", $href);
    }

    /**
     * @test
     */
    public function getNextLink(): void
    {
        $provider = $this->createProvider(0, 0, 0, 0, 3, 10);

        $link = $provider->getNextLink("https://example.com/api/users", "");
        $href = $link !== null ? $link->getHref() : "";

        $this->assertEquals("https://example.com/api/users?page%5Bcursor%5D=3&page%5Bsize%5D=10", $href);
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
