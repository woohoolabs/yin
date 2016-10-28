<?php
namespace WoohooLabs\Yin\Tests\JsonApi\Schema;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Schema\Link;
use WoohooLabs\Yin\JsonApi\Schema\Links;
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
        $self = new Link("http://example.com/api/users");
        $links = ["self" => $self, "related" => $self];

        $linksObject = $this->createLinks("", $links);
        $this->assertArrayHasKey("self", $linksObject->transform());
        $this->assertArrayHasKey("related", $linksObject->transform());
    }

    /**
     * @test
     */
    public function getSelfWhenEmpty()
    {
        $linksObject = $this->createLinks();
        $this->assertNull($linksObject->getSelf());
    }

    /**
     * @test
     */
    public function getSelfWhenNotEmpty()
    {
        $self = new Link("http://example.com/api/users");

        $linksObject = $this->createLinks()->setSelf($self);
        $this->assertEquals($self, $linksObject->getSelf());
    }

    /**
     * @test
     */
    public function getRelatedWhenNotEmpty()
    {
        $related = new Link("http://example.com/api/users");

        $linksObject = $this->createLinks()->setRelated($related);
        $this->assertEquals($related, $linksObject->getRelated());
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

        $linksObject = $this->createLinks()->setFirst($first);
        $this->assertEquals($first, $linksObject->getFirst());
    }

    /**
     * @test
     */
    public function getLastWhenNotEmpty()
    {
        $last = new Link("http://example.com/api/users?page[number]=10");

        $linksObject = $this->createLinks()->setLast($last);
        $this->assertEquals($last, $linksObject->getLast());
    }

    /**
     * @test
     */
    public function getPrevWhenNotEmpty()
    {
        $prev = new Link("http://example.com/api/users?page[number]=4");

        $linksObject = $this->createLinks()->setPrev($prev);
        $this->assertEquals($prev, $linksObject->getPrev());
    }

    /**
     * @test
     */
    public function getNextWhenNotEmpty()
    {
        $next = new Link("http://example.com/api/users?page[number]=6");

        $linksObject = $this->createLinks()->setNext($next);
        $this->assertEquals($next, $linksObject->getNext());
    }

    /**
     * @test
     */
    public function setPagination()
    {
        $uri = "http://example.com/api/users/";
        $pagination = new StubPaginationLinkProvider();

        $linksObject = $this->createLinks()->setPagination($uri, $pagination);
        $this->assertEquals(new Link($uri . "self"), $linksObject->getSelf());
        $this->assertEquals(new Link($uri . "first"), $linksObject->getFirst());
        $this->assertEquals(new Link($uri . "last"), $linksObject->getLast());
        $this->assertEquals(new Link($uri . "prev"), $linksObject->getPrev());
        $this->assertEquals(new Link($uri . "next"), $linksObject->getNext());
    }

    /**
     * @test
     */
    public function getLink()
    {
        $self = new Link("http://example.com/api/users");

        $linksObject = $this->createLinks()->setLink("self", $self);
        $this->assertEquals($self, $linksObject->getLink("self"));
    }

    /**
     * @test
     */
    public function getMultipleLinks()
    {
        $self = new Link("http://example.com/api/users/1");
        $related = new Link("http://example.com/api/people/1");
        $links = ["self" => $self, "related" => $related];

        $linksObject = $this->createLinks()->setLinks($links);
        $this->assertEquals($self, $linksObject->getLink("self"));
        $this->assertEquals($related, $linksObject->getLink("related"));
    }

    /**
     * @param string $baseUri
     * @param \WoohooLabs\Yin\JsonApi\Schema\Link[] $links
     * @return \WoohooLabs\Yin\JsonApi\Schema\Links
     */
    private function createLinks($baseUri = "", array $links = [])
    {
        return new Links($baseUri, $links);
    }
}
