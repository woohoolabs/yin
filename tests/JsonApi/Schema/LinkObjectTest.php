<?php
namespace WoohooLabsTest\Yin\JsonApi\Schema;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Schema\LinkObject;

class LinkObjectTest extends PHPUnit_Framework_TestCase
{
    public function testGetHref()
    {
        $href = "http://example.com/api/users";

        $link = $this->createLink($href);
        $this->assertEquals($href, $link->getHref());
    }

    public function testGetEmptyMeta()
    {
        $href = "http://example.com/api/users";

        $link = $this->createLink($href);
        $this->assertEquals([], $link->getMeta());
    }

    public function testGetMeta()
    {
        $meta = ["abc" => "def"];

        $link = $this->createLinkWithMeta("", $meta);
        $this->assertEquals($meta, $link->getMeta());
    }

    public function testAbsoluteLinkWithMetaTransform()
    {
        $href = "http://example.com/api/users";
        $meta = ["abc" => "def"];

        $link = $this->createLinkWithMeta($href, $meta);

        $transformedLink = [
          "href" => $href,
          "meta" => $meta
        ];
        $this->assertEquals($transformedLink, $link->transform(""));
    }

    public function testRelativeLinkWithoutMetaTransform()
    {
        $baseUri = "http://example.com/api";
        $href = "/users";

        $link = $this->createLink($href);

        $transformedLink = [
            "href" => $baseUri . $href
        ];
        $this->assertEquals($transformedLink, $link->transform($baseUri));
    }

    private function createLink($href)
    {
        return new LinkObject($href);
    }

    private function createLinkWithMeta($href, array $meta)
    {
        return new LinkObject($href, $meta);
    }
}
