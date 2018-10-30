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
    public function createWithoutBaseUri()
    {
        $links = RelationshipLinks::createWithoutBaseUri();

        $this->assertEquals("", $links->getBaseUri());
    }

    /**
     * @test
     */
    public function createWithBaseUri()
    {
        $links = RelationshipLinks::createWithBaseUri("http://example.com");

        $this->assertEquals("http://example.com", $links->getBaseUri());
    }

    /**
     * @test
     */
    public function setBaseUri()
    {
        $links = $this->createRelationshipLinks();

        $links->setBaseUri("http://example.com");

        $this->assertEquals("http://example.com", $links->getBaseUri());
    }

    /**
     * @test
     */
    public function transform()
    {
        $links = $this->createRelationshipLinks(
            "",
            new Link("http://example.com/articles/1/relationships/author"),
            new Link("http://example.com/articles/1/author")
        );

        $transformedLinks = $links->transform();

        $this->assertArrayHasKey("self", $transformedLinks);
        $this->assertArrayHasKey("related", $transformedLinks);
    }

    /**
     * @test
     */
    public function getSelfWhenEmpty()
    {
        $links = $this->createRelationshipLinks();

        $this->assertNull($links->getSelf());
    }

    /**
     * @test
     */
    public function getSelfWhenNotEmpty()
    {
        $self = new Link("http://example.com/api/users");

        $links = $this->createRelationshipLinks()->setSelf($self);

        $this->assertEquals($self, $links->getSelf());
    }

    /**
     * @test
     */
    public function getRelatedWhenEmpty()
    {
        $links = $this->createRelationshipLinks();

        $this->assertNull($links->getRelated());
    }

    /**
     * @test
     */
    public function getRelatedWhenNotEmpty()
    {
        $related = new Link("http://example.com/articles/1/author");

        $links = $this->createRelationshipLinks()->setRelated($related);

        $this->assertEquals($related, $links->getRelated());
    }

    /**
     * @test
     */
    public function getLinkWhenEmpty()
    {
        $links = $this->createRelationshipLinks();

        $this->assertNull($links->getLink("self"));
    }

    /**
     * @test
     */
    public function getLinkWhenNotEmpty()
    {
        $self = new Link("http://example.com/api/users");

        $links = $this->createRelationshipLinks()->setSelf($self);

        $this->assertEquals($self, $links->getLink("self"));
    }

    private function createRelationshipLinks(string $baseUri = "", ?Link $self = null, ?Link $related = null): RelationshipLinks
    {
        return new RelationshipLinks($baseUri, $self, $related);
    }
}
