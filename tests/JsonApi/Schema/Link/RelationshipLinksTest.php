<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Schema\Link;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Schema\Link\Link;
use WoohooLabs\Yin\JsonApi\Schema\Link\RelationshipLinks;

class RelationshipLinksTest extends TestCase
{
    /**
     * @test
     */
    public function createWithoutBaseUri(): void
    {
        $links = RelationshipLinks::createWithoutBaseUri();

        $this->assertEquals("", $links->getBaseUri());
    }

    /**
     * @test
     */
    public function createWithBaseUri(): void
    {
        $links = RelationshipLinks::createWithBaseUri("https://example.com");

        $this->assertEquals("https://example.com", $links->getBaseUri());
    }

    /**
     * @test
     */
    public function setBaseUri(): void
    {
        $links = $this->createRelationshipLinks();

        $links->setBaseUri("https://example.com");

        $this->assertEquals("https://example.com", $links->getBaseUri());
    }

    /**
     * @test
     */
    public function transform(): void
    {
        $links = $this->createRelationshipLinks(
            "",
            new Link("https://example.com/articles/1/relationships/author"),
            new Link("https://example.com/articles/1/author")
        );

        $transformedLinks = $links->transform();

        $this->assertArrayHasKey("self", $transformedLinks);
        $this->assertArrayHasKey("related", $transformedLinks);
    }

    /**
     * @test
     */
    public function getSelfWhenEmpty(): void
    {
        $links = $this->createRelationshipLinks();

        $this->assertNull($links->getSelf());
    }

    /**
     * @test
     */
    public function getSelfWhenNotEmpty(): void
    {
        $self = new Link("https://example.com/api/users");

        $links = $this->createRelationshipLinks()->setSelf($self);

        $this->assertEquals($self, $links->getSelf());
    }

    /**
     * @test
     */
    public function getRelatedWhenEmpty(): void
    {
        $links = $this->createRelationshipLinks();

        $this->assertNull($links->getRelated());
    }

    /**
     * @test
     */
    public function getRelatedWhenNotEmpty(): void
    {
        $related = new Link("https://example.com/articles/1/author");

        $links = $this->createRelationshipLinks()->setRelated($related);

        $this->assertEquals($related, $links->getRelated());
    }

    /**
     * @test
     */
    public function getLinkWhenEmpty(): void
    {
        $links = $this->createRelationshipLinks();

        $this->assertNull($links->getLink("self"));
    }

    /**
     * @test
     */
    public function getLinkWhenNotEmpty(): void
    {
        $self = new Link("https://example.com/api/users");

        $links = $this->createRelationshipLinks()->setSelf($self);

        $this->assertEquals($self, $links->getLink("self"));
    }

    private function createRelationshipLinks(string $baseUri = "", ?Link $self = null, ?Link $related = null): RelationshipLinks
    {
        return new RelationshipLinks($baseUri, $self, $related);
    }
}
