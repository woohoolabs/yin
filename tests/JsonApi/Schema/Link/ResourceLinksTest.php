<?php

declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Schema\Link;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Schema\Link\Link;
use WoohooLabs\Yin\JsonApi\Schema\Link\ResourceLinks;

class ResourceLinksTest extends TestCase
{
    /**
     * @test
     */
    public function createWithoutBaseUri(): void
    {
        $links = ResourceLinks::createWithoutBaseUri();

        $this->assertEquals("", $links->getBaseUri());
    }

    /**
     * @test
     */
    public function createWithBaseUri(): void
    {
        $links = ResourceLinks::createWithBaseUri("https://example.com");

        $this->assertEquals("https://example.com", $links->getBaseUri());
    }

    /**
     * @test
     */
    public function setBaseUri(): void
    {
        $links = $this->createResourceLinks();

        $links->setBaseUri("https://example.com");

        $this->assertEquals("https://example.com", $links->getBaseUri());
    }

    /**
     * @test
     */
    public function transform(): void
    {
        $links = $this->createResourceLinks("", new Link("https://example.com/articles/1"));

        $this->assertArrayHasKey("self", $links->transform());
    }

    /**
     * @test
     */
    public function getSelfWhenEmpty(): void
    {
        $links = $this->createResourceLinks();

        $this->assertNull($links->getSelf());
    }

    /**
     * @test
     */
    public function getSelfWhenNotEmpty(): void
    {
        $self = new Link("https://example.com/api/users");

        $links = $this->createResourceLinks()->setSelf($self);

        $this->assertEquals($self, $links->getSelf());
    }

    /**
     * @test
     */
    public function getLinkWhenEmpty(): void
    {
        $links = $this->createResourceLinks();

        $this->assertNull($links->getLink("self"));
    }

    /**
     * @test
     */
    public function getLinkWhenNotEmpty(): void
    {
        $self = new Link("https://example.com/api/users");

        $links = $this->createResourceLinks()->setSelf($self);

        $this->assertEquals($self, $links->getLink("self"));
    }

    private function createResourceLinks(string $baseUri = "", ?Link $self = null): ResourceLinks
    {
        return new ResourceLinks($baseUri, $self);
    }
}
