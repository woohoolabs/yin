<?php
namespace WoohooLabsTest\Yin\JsonApi\Schema\Pagination;

use PHPUnit_Framework_TestCase;
use WoohooLabsTest\Yin\JsonApi\Utils\StubCursorBasedPaginationProvider;

class CursorBasedPaginationProviderTraitTest extends PHPUnit_Framework_TestCase
{
    public function testGetSelfLinkWhenCurrentItemIsNull()
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

    public function testGetSelfLinkWhenOnlyPathProvided()
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

    public function testGetSelfLinkWhenQueryStringSeparatorIsProvided()
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

    public function testGetSelfLinkWhenQueryStringIsProvided()
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

    public function testGetFirstLinkWhenFirstItemIsNull()
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

    public function testGetFirstLink()
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

    public function testGetLastLinkWhenLastItemIsNull()
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

    public function testGetLastLink()
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

    public function testGetPrevLink()
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

    public function testGetNextLink()
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
     * @return \WoohooLabsTest\Yin\JsonApi\Utils\StubCursorBasedPaginationProvider
     */
    private function createProvider($firstItem, $lastItem, $currentItem, $previousItem, $nextItem)
    {
        return new StubCursorBasedPaginationProvider($firstItem, $lastItem, $currentItem, $previousItem, $nextItem);
    }
}
