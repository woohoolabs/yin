<?php
namespace WoohooLabsTest\Yin\JsonApi\Schema;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Schema\Link;

class LinkTest extends PHPUnit_Framework_TestCase
{
    public function testGetHref()
    {
        $link = $this->createLink("http://example.com");
        $this->assertEquals("http://example.com", $link->getHref());
    }

    public function testAbsoluteLinkTransform()
    {
        $link = $this->createLink("http://example.com");
        $this->assertEquals("http://example.com", $link->transform(""));
    }

    public function testRelativeLinkTransform()
    {
        $link = $this->createLink("/api/users");
        $this->assertEquals("http://example.com/api/users", $link->transform("http://example.com"));
    }

    private function createLink($href)
    {
        return new Link($href);
    }
}
