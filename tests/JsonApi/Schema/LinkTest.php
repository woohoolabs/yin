<?php
namespace WoohooLabsTest\Yin\JsonApi\Schema;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Schema\Link;

class LinkTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function getHref()
    {
        $href = "http://example.com";

        $link = $this->createLink($href);
        $this->assertEquals($href, $link->getHref());
    }

    /**
     * @test
     */
    public function transformAbsoluteLink()
    {
        $href = "http://example.com/api/users";

        $link = $this->createLink($href);
        $this->assertEquals($href, $link->transform(""));
    }

    /**
     * @test
     */
    public function transformRelativeLink()
    {
        $baseUri = "http://example.com/api";
        $href = "/users";

        $link = $this->createLink($href);
        $this->assertEquals($baseUri . $href, $link->transform($baseUri));
    }

    private function createLink($href)
    {
        return new Link($href);
    }
}
