<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Schema\Link;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Schema\Link\ErrorLinks;
use WoohooLabs\Yin\JsonApi\Schema\Link\Link;

class ErrorLinksTest extends TestCase
{
    /**
     * @test
     */
    public function createWithoutBaseUri()
    {
        $links = ErrorLinks::createWithoutBaseUri();

        $this->assertEquals("", $links->getBaseUri());
    }

    /**
     * @test
     */
    public function createWithBaseUri()
    {
        $links = ErrorLinks::createWithBaseUri("http://example.com");

        $this->assertEquals("http://example.com", $links->getBaseUri());
    }

    /**
     * @test
     */
    public function setBaseUri()
    {
        $links = $this->createErrorLinks();

        $links->setBaseUri("http://example.com");
        $this->assertEquals("http://example.com", $links->getBaseUri());
    }

    /**
     * @test
     */
    public function transform()
    {
        $linksObject = $this->createErrorLinks(
            "",
            new Link("http://example.com/api/errors/1"),
            [
                new Link("http://example.com/api/errors/type/1"),
                new Link("http://example.com/api/errors/type/2"),
            ]
        );

        $transformedLinks = $linksObject->transform();
        $this->assertArrayHasKey("about", $transformedLinks);
        $this->assertArrayHasKey("type", $transformedLinks);
        $this->assertCount(2, $transformedLinks["type"]);
    }

    /**
     * @test
     */
    public function getAboutWhenEmpty()
    {
        $linksObject = $this->createErrorLinks();
        $this->assertNull($linksObject->getAbout());
    }

    /**
     * @test
     */
    public function getAboutWhenNotEmpty()
    {
        $about = new Link("http://example.com/about");

        $linksObject = $this->createErrorLinks()->setAbout($about);
        $this->assertEquals($about, $linksObject->getAbout());
    }

    /**
     * @test
     */
    public function getTypeWhenEmpty()
    {
        $linksObject = $this->createErrorLinks();
        $this->assertEquals([], $linksObject->getType());
    }

    /**
     * @test
     */
    public function getTypeWhenNotEmpty()
    {
        $typeLink = new Link("http://example.com/errors/404");

        $linksObject = $this->createErrorLinks()->addType($typeLink);
        $this->assertContains($typeLink, $linksObject->getType());
    }

    /**
     * @param Link[] $links
     */
    private function createErrorLinks(string $baseUri = "", ?Link $about = null, array $types = []): ErrorLinks
    {
        return new ErrorLinks($baseUri, $about, $types);
    }
}
