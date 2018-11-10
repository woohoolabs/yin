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
    public function createWithoutBaseUri()
    {
        $links = ResourceLinks::createWithoutBaseUri();

        $this->assertEquals("", $links->getBaseUri());
    }

    /**
     * @test
     */
    public function createWithBaseUri()
    {
        $links = ResourceLinks::createWithBaseUri("http://example.com");

        $this->assertEquals("http://example.com", $links->getBaseUri());
    }

    /**
     * @test
     */
    public function setBaseUri()
    {
        $links = $this->createResourceLinks();

        $links->setBaseUri("http://example.com");

        $this->assertEquals("http://example.com", $links->getBaseUri());
    }

    /**
     * @test
     */
    public function transform()
    {
        $links = $this->createResourceLinks("", new Link("http://example.com/articles/1"));

        $this->assertArrayHasKey("self", $links->transform());
    }

    /**
     * @test
     */
    public function getSelfWhenEmpty()
    {
        $links = $this->createResourceLinks();

        $this->assertNull($links->getSelf());
    }

    /**
     * @test
     */
    public function getSelfWhenNotEmpty()
    {
        $self = new Link("http://example.com/api/users");

        $links = $this->createResourceLinks()->setSelf($self);

        $this->assertEquals($self, $links->getSelf());
    }

    /**
     * @test
     */
    public function getLinkWhenEmpty()
    {
        $links = $this->createResourceLinks();

        $this->assertNull($links->getLink("self"));
    }

    /**
     * @test
     */
    public function getLinkWhenNotEmpty()
    {
        $self = new Link("http://example.com/api/users");

        $links = $this->createResourceLinks()->setSelf($self);

        $this->assertEquals($self, $links->getLink("self"));
    }

    private function createResourceLinks(string $baseUri = "", ?Link $self = null): ResourceLinks
    {
        return new ResourceLinks($baseUri, $self);
    }
}
