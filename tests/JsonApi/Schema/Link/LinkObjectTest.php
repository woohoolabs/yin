<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Schema\Link;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Schema\Link\LinkObject;

class LinkObjectTest extends TestCase
{
    /**
     * @test
     */
    public function getHref(): void
    {
        $href = "https://example.com/api/users";

        $link = $this->createLinkObject($href);
        $this->assertEquals($href, $link->getHref());
    }

    /**
     * @test
     */
    public function getEmptyMeta(): void
    {
        $href = "https://example.com/api/users";

        $link = $this->createLinkObject($href);
        $this->assertEquals([], $link->getMeta());
    }

    /**
     * @test
     */
    public function getMeta(): void
    {
        $meta = ["abc" => "def"];

        $link = $this->createLinkWithMeta("", $meta);
        $this->assertEquals($meta, $link->getMeta());
    }

    /**
     * @test
     */
    public function transformAbsoluteLinkWithMeta(): void
    {
        $href = "https://example.com/api/users";
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
    public function transformRelativeLinkWithoutMeta(): void
    {
        $baseUri = "https://example.com/api";
        $href = "/users";

        $link = $this->createLinkObject($href);

        $transformedLink = [
            "href" => $baseUri . $href,
        ];
        $this->assertEquals($transformedLink, $link->transform($baseUri));
    }

    private function createLinkObject(string $href): LinkObject
    {
        return new LinkObject($href);
    }

    private function createLinkWithMeta(string $href, array $meta): LinkObject
    {
        return new LinkObject($href, $meta);
    }
}
