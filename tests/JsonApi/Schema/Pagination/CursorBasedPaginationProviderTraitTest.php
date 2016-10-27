<?php
namespace WoohooLabsTest\Yin\JsonApi\Schema\Pagination;

use PHPUnit\Framework\TestCase;
use WoohooLabsTest\Yin\JsonApi\Double\StubCursorBasedPaginationProvider;

class CursorBasedPaginationProviderTraitTest extends TestCase
{
    /**
     * @test
     */
    public function getSelfLinkWhenCurrentItemIsNull()
    {
        $url = "http://example.com/api/users?";
        $firstItem = 0;
        $lastItem = 4;
        $currentItem = null;
        $previousItem = 1;
        $nextItem = 3;

        $provider = $this->createProvider($firstItem, $lastItem, $currentItem, $previousItem, $nextItem);
        $this->assertNull($provider->getSelfLink($url));
    }

    /**
     * @test
     */
    public function getSelfLinkWhenOnlyPathProvided()
    {
        $url = "http://example.com/api/users";
        $firstItem = 0;
        $lastItem = 4;
        $currentItem = 2;
        $previousItem = 1;
        $nextItem = 3;

        $provider = $this->createProvider($firstItem, $lastItem, $currentItem, $previousItem, $nextItem);
        $this->assertEquals("$url?page[cursor]=$currentItem", $provider->getSelfLink($url)->getHref());
    }

    /**
     * @test
     */
    public function getSelfLinkWhenQueryStringSeparatorIsProvided()
    {
        $url = "http://example.com/api/users?";
        $firstItem = 0;
        $lastItem = 4;
        $currentItem = 2;
        $previousItem = 1;
        $nextItem = 3;

        $provider = $this->createProvider($firstItem, $lastItem, $currentItem, $previousItem, $nextItem);
        $this->assertEquals("{$url}page[cursor]=$currentItem", $provider->getSelfLink($url)->getHref());
    }

    /**
     * @test
     */
    public function getSelfLinkWhenQueryStringIsProvided()
    {
        $url = "http://example.com/api/users?a=b";
        $firstItem = 0;
        $lastItem = 4;
        $currentItem = 2;
        $previousItem = 1;
        $nextItem = 3;

        $provider = $this->createProvider($firstItem, $lastItem, $currentItem, $previousItem, $nextItem);
        $this->assertEquals("{$url}&page[cursor]=$currentItem", $provider->getSelfLink($url)->getHref());
    }

    /**
     * @test
     */
    public function getFirstLinkWhenFirstItemIsNull()
    {
        $url = "http://example.com/api/users?";
        $firstItem = null;
        $lastItem = 4;
        $currentItem = 2;
        $previousItem = 1;
        $nextItem = 3;

        $provider = $this->createProvider($firstItem, $lastItem, $currentItem, $previousItem, $nextItem);
        $this->assertNull($provider->getFirstLink($url));
    }

    /**
     * @test
     */
    public function getFirstLink()
    {
        $url = "http://example.com/api/users";
        $firstItem = 0;
        $lastItem = 4;
        $currentItem = 2;
        $previousItem = 1;
        $nextItem = 3;

        $provider = $this->createProvider($firstItem, $lastItem, $currentItem, $previousItem, $nextItem);
        $this->assertEquals("$url?page[cursor]=$firstItem", $provider->getFirstLink($url)->getHref());
    }

    /**
     * @test
     */
    public function getLastLinkWhenLastItemIsNull()
    {
        $url = "http://example.com/api/users?";
        $firstItem = null;
        $lastItem = null;
        $currentItem = 2;
        $previousItem = 1;
        $nextItem = 3;

        $provider = $this->createProvider($firstItem, $lastItem, $currentItem, $previousItem, $nextItem);
        $this->assertNull($provider->getLastLink($url));
    }

    /**
     * @test
     */
    public function getLastLink()
    {
        $url = "http://example.com/api/users";
        $firstItem = 0;
        $lastItem = 4;
        $currentItem = 2;
        $previousItem = 1;
        $nextItem = 3;

        $provider = $this->createProvider($firstItem, $lastItem, $currentItem, $previousItem, $nextItem);
        $this->assertEquals("$url?page[cursor]=$lastItem", $provider->getLastLink($url)->getHref());
    }

    /**
     * @test
     */
    public function getPrevLink()
    {
        $url = "http://example.com/api/users";
        $firstItem = 0;
        $lastItem = 4;
        $currentItem = 2;
        $previousItem = 1;
        $nextItem = 3;

        $provider = $this->createProvider($firstItem, $lastItem, $currentItem, $previousItem, $nextItem);
        $this->assertEquals("$url?page[cursor]=$previousItem", $provider->getPrevLink($url)->getHref());
    }

    /**
     * @test
     */
    public function getNextLink()
    {
        $url = "http://example.com/api/users";
        $firstItem = 0;
        $lastItem = 4;
        $currentItem = 2;
        $previousItem = 1;
        $nextItem = 3;

        $provider = $this->createProvider($firstItem, $lastItem, $currentItem, $previousItem, $nextItem);
        $this->assertEquals("$url?page[cursor]=$nextItem", $provider->getNextLink($url)->getHref());
    }

    /**
     * @param mixed $firstItem
     * @param mixed $lastItem
     * @param mixed $currentItem
     * @param mixed $previousItem
     * @param mixed $nextItem
     * @return \WoohooLabsTest\Yin\JsonApi\Double\StubCursorBasedPaginationProvider
     */
    private function createProvider($firstItem, $lastItem, $currentItem, $previousItem, $nextItem)
    {
        return new StubCursorBasedPaginationProvider($firstItem, $lastItem, $currentItem, $previousItem, $nextItem);
    }
}
