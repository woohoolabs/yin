<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Schema;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Schema\LinkObject;

class LinkObjectTest extends TestCase
{
    /**
     * @test
     */
    public function getHref()
    {
        $href = "http://example.com/api/users";

        $link = $this->createLink($href);
        $this->assertEquals($href, $link->getHref());
    }

    /**
     * @test
     */
    public function getEmptyMeta()
    {
        $href = "http://example.com/api/users";

        $link = $this->createLink($href);
        $this->assertEquals([], $link->getMeta());
    }

    /**
     * @test
     */
    public function getMeta()
    {
        $meta = ["abc" => "def"];

        $link = $this->createLinkWithMeta("", $meta);
        $this->assertEquals($meta, $link->getMeta());
    }

    /**
     * @test
     */
    public function transformAbsoluteLinkWithMeta()
    {
        $href = "http://example.com/api/users";
        $meta = ["abc" => "def"];

        $link = $this->createLinkWithMeta($href, $meta);

        $transformedLink = [
          "href" => $href,
          "meta" => $meta,
        ];
        $this->assertEquals($transformedLink, $link->transform(""));
    }

    /**
     * @test
     */
    public function transformRelativeLinkWithoutMeta()
    {
        $baseUri = "http://example.com/api";
        $href = "/users";

        $link = $this->createLink($href);

        $transformedLink = [
            "href" => $baseUri . $href,
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
