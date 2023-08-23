<?php

declare(strict_types=1);

namespace Devleand\Yin\Tests\JsonApi\Schema\Link;

use PHPUnit\Framework\TestCase;
use Devleand\Yin\JsonApi\Schema\Link\ProfileLinkObject;

class ProfileLinkObjectTest extends TestCase
{
    /**
     * @test
     */
    public function getAliases(): void
    {
        $link = $this->createProfileLinkObject(["keyword" => "alias"]);

        $this->assertEquals(["keyword" => "alias"], $link->getAliases());
    }

    /**
     * @test
     */
    public function getAliasWhenPresent(): void
    {
        $link = $this->createProfileLinkObject(["keyword" => "alias"]);

        $this->assertEquals("alias", $link->getAlias("keyword"));
    }

    /**
     * @test
     */
    public function getAliasWhenNotPresent(): void
    {
        $link = $this->createProfileLinkObject(["keyword" => "alias"]);

        $this->assertEquals("", $link->getAlias("key"));
    }

    /**
     * @test
     */
    public function addAliases(): void
    {
        $link = $this->createProfileLinkObject();

        $link->addAlias("keyword1", "alias1");
        $link->addAlias("keyword2", "alias2");

        $this->assertEquals(["keyword1" => "alias1", "keyword2" => "alias2"], $link->getAliases());
    }

    /**
     * @test
     */
    public function transformLinkWithAlias(): void
    {
        $link = $this->createProfileLinkObject(["keyword" => "alias"]);

        $transformedLink = $link->transform("");

        $this->assertArrayHasKey("aliases", $transformedLink);
        $this->assertEquals(["keyword" => "alias"], $transformedLink["aliases"]);
    }

    /**
     * @test
     */
    public function transformLinkWithoutAlias(): void
    {
        $link = $this->createProfileLinkObject([]);

        $transformedLink = $link->transform("");

        $this->assertArrayNotHasKey("aliases", $transformedLink);
    }

    private function createProfileLinkObject(array $aliases = []): ProfileLinkObject
    {
        return new ProfileLinkObject("", [], $aliases);
    }
}
