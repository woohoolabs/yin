<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Schema\Link;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Schema\Link\Link;
use WoohooLabs\Yin\JsonApi\Schema\Link\Links;
use WoohooLabs\Yin\Tests\JsonApi\Double\StubPaginationLinkProvider;

class LinksTest extends TestCase
{
    /**
     * @test
     */
    public function createWithoutBaseUri()
    {
        $links = Links::createWithoutBaseUri([]);

        $this->assertEquals("", $links->getBaseUri());
    }

    /**
     * @test
     */
    public function createWithBaseUri()
    {
        $links = Links::createWithBaseUri("http://example.com", []);

        $this->assertEquals("http://example.com", $links->getBaseUri());
    }

    /**
     * @test
     */
    public function setBaseUri()
    {
        $links = $this->createLinks();

        $links->setBaseUri("http://example.com");

        $this->assertEquals("http://example.com", $links->getBaseUri());
    }

    /**
     * @test
     */
    public function transform()
    {
        $links = $this->createLinks(
            "",
            [
                "self" => new Link("http://example.com/articles/1/relationships/author"),
                "related" => new Link("http://example.com/articles/1/author"),
            ]
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
        $links = $this->createLinks();

        $this->assertNull($links->getSelf());
    }

    /**
     * @test
     */
    public function getSelfWhenNotEmpty()
    {
        $self = new Link("http://example.com/api/users");

        $links = $this->createLinks()->setSelf($self);

        $this->assertEquals($self, $links->getSelf());
    }

    /**
     * @test
     */
    public function getRelatedWhenNotEmpty()
    {
        $related = new Link("http://example.com/api/users");

        $links = $this->createLinks()->setRelated($related);

        $this->assertEquals($related, $links->getRelated());
    }

    /**
     * @test
     */
    public function getFirstWhenEmpty()
    {
        $linksObject = $this->createLinks();

        $this->assertNull($linksObject->getFirst());
    }

    /**
     * @test
     */
    public function getFirstWhenNotEmpty()
    {
        $first = new Link("http://example.com/api/users?page[number]=1");

        $links = $this->createLinks()->setFirst($first);

        $this->assertEquals($first, $links->getFirst());
    }

    /**
     * @test
     */
    public function getLastWhenNotEmpty()
    {
        $last = new Link("http://example.com/api/users?page[number]=10");

        $links = $this->createLinks()->setLast($last);

        $this->assertEquals($last, $links->getLast());
    }

    /**
     * @test
     */
    public function getPrevWhenNotEmpty()
    {
        $prev = new Link("http://example.com/api/users?page[number]=4");

        $links = $this->createLinks()->setPrev($prev);

        $this->assertEquals($prev, $links->getPrev());
    }

    /**
     * @test
     */
    public function getNextWhenNotEmpty()
    {
        $next = new Link("http://example.com/api/users?page[number]=6");

        $links = $this->createLinks()->setNext($next);

        $this->assertEquals($next, $links->getNext());
    }

    /**
     * @test
     */
    public function setPagination()
    {
        $uri = "http://example.com/api/users/";
        $pagination = new StubPaginationLinkProvider();

        $links = $this->createLinks()->setPagination($uri, $pagination);

        $this->assertEquals(new Link($uri . "self"), $links->getSelf());
        $this->assertEquals(new Link($uri . "first"), $links->getFirst());
        $this->assertEquals(new Link($uri . "last"), $links->getLast());
        $this->assertEquals(new Link($uri . "prev"), $links->getPrev());
        $this->assertEquals(new Link($uri . "next"), $links->getNext());
    }

    /**
     * @test
     */
    public function getLink()
    {
        $self = new Link("http://example.com/api/users");

        $links = $this->createLinks()->setLink("self", $self);

        $this->assertEquals($self, $links->getLink("self"));
    }

    /**
     * @test
     */
    public function getMultipleLinks()
    {
        $self = new Link("http://example.com/api/users/1");
        $related = new Link("http://example.com/api/people/1");
        $links = ["self" => $self, "related" => $related];

        $links = $this->createLinks()->setLinks($links);

        $this->assertEquals($self, $links->getLink("self"));
        $this->assertEquals($related, $links->getLink("related"));
    }

    /**
     * @param Link[] $links
     */
    private function createLinks(string $baseUri = "", array $links = []): Links
    {
        return new Links($baseUri, $links);
    }
}
