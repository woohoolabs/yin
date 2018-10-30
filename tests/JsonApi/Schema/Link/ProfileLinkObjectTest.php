<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Schema\Link;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Schema\Link\ProfileLinkObject;

class ProfileLinkObjectTest extends TestCase
{
    /**
     * @test
     */
    public function getAliases()
    {
        $link = $this->createProfileLinkObject(["keyword" => "alias"]);

        $this->assertEquals(["keyword" => "alias"], $link->getAliases());
    }

    /**
     * @test
     */
    public function getAliasWhenPresent()
    {
        $link = $this->createProfileLinkObject(["keyword" => "alias"]);

        $this->assertEquals("alias", $link->getAlias("keyword"));
    }

    /**
     * @test
     */
    public function getAliasWhenNotPresent()
    {
        $link = $this->createProfileLinkObject(["keyword" => "alias"]);

        $this->assertEquals("", $link->getAlias("key"));
    }

    /**
     * @test
     */
    public function transformLinkWithAlias()
    {
        $link = $this->createProfileLinkObject(["keyword" => "alias"]);

        $transformedLink = $link->transform("");

        $this->assertArrayHasKey("aliases", $transformedLink);
        $this->assertEquals(["keyword" => "alias"], $transformedLink["aliases"]);
    }

    /**
     * @test
     */
    public function transformLinkWithoutAlias()
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
