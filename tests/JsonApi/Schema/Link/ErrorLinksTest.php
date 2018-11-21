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
        $links = ErrorLinks::createWithBaseUri("https://example.com");

        $this->assertEquals("https://example.com", $links->getBaseUri());
    }

    /**
     * @test
     */
    public function setBaseUri()
    {
        $links = $this->createErrorLinks();

        $links->setBaseUri("https://example.com");

        $this->assertEquals("https://example.com", $links->getBaseUri());
    }

    /**
     * @test
     */
    public function transform()
    {
        $linksObject = $this->createErrorLinks(
            "",
            new Link("https://example.com/api/errors/1"),
            [
                new Link("https://example.com/api/errors/type/1"),
                new Link("https://example.com/api/errors/type/2"),
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
        $about = new Link("https://example.com/about");

        $linksObject = $this->createErrorLinks()->setAbout($about);
        $this->assertEquals($about, $linksObject->getAbout());
    }

    /**
     * @test
     */
    public function getTypeWhenEmpty()
    {
        $linksObject = $this->createErrorLinks();
        $this->assertEquals([], $linksObject->getTypes());
    }

    /**
     * @test
     */
    public function getTypeWhenNotEmpty()
    {
        $typeLink = new Link("https://example.com/errors/404");

        $linksObject = $this->createErrorLinks()->addType($typeLink);

        $this->assertContains($typeLink, $linksObject->getTypes());
    }

    /**
     * @test
     */
    public function setTypes()
    {
        $typeLink1 = new Link("https://example.com/errors/404");
        $typeLink2 = new Link("https://example.com/errors/405");

        $linksObject = $this->createErrorLinks()->setTypes([$typeLink1, $typeLink2]);

        $this->assertCount(2, $linksObject->getTypes());
        $this->assertEquals($typeLink1, $linksObject->getTypes()[0]);
        $this->assertEquals($typeLink2, $linksObject->getTypes()[1]);
    }

    /**
     * @test
     */
    public function setTypesWithSameHref()
    {
        $typeLink = new Link("https://example.com/errors/404");

        $linksObject = $this->createErrorLinks()->setTypes([$typeLink, $typeLink]);

        $this->assertCount(1, $linksObject->getTypes());
        $this->assertEquals($typeLink, $linksObject->getTypes()[0]);
    }

    /**
     * @param Link[] $links
     */
    private function createErrorLinks(string $baseUri = "", ?Link $about = null, array $types = []): ErrorLinks
    {
        return new ErrorLinks($baseUri, $about, $types);
    }
}
