<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Schema\Link;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Schema\Link\Link;

class LinkTest extends TestCase
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

    private function createLink($href): Link
    {
        return new Link($href);
    }
}
