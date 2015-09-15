<?php
namespace WoohooLabsTest\Yin\JsonApi\Schema;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Schema\Link;
use WoohooLabs\Yin\JsonApi\Schema\Links;
use WoohooLabsTest\Yin\JsonApi\Utils\StubPaginationLinkProvider;

class LinksTest extends PHPUnit_Framework_TestCase
{
    public function testCreateAbsolute()
    {
        $links = ["self" => new Link("http://example.com/api/users")];

        $linksObject = $this->createLinks("", $links);
        $this->assertEquals($linksObject, Links::createAbsolute($links));
    }

    public function testCreateRelative()
    {
        $baseUri = "http://example.com/api";
        $links = ["self" => new Link("/users")];

        $linksObject = $this->createLinks($baseUri, $links);
        $this->assertEquals($linksObject, Links::createRelative($baseUri, $links));
    }

    public function testCreateAbsoluteWithSelf()
    {
        $self = new Link("http://example.com/api/users");
        $links = ["self" => $self];

        $linksObject = $this->createLinks("", $links);
        $this->assertEquals($linksObject, Links::createAbsoluteWithSelf($self));
    }

    public function testCreateRelativeWithSelf()
    {
        $baseUri = "http://example.com/api";
        $self = new Link("/users");
        $links = ["self" => $self];

        $linksObject = $this->createLinks($baseUri, $links);
        $this->assertEquals($linksObject, Links::createRelativeWithSelf($baseUri, $self));
    }

    public function testCreateAbsoluteWithRelated()
    {
        $self = new Link("http://example.com/api/users");
        $links = ["related" => $self];

        $linksObject = $this->createLinks("", $links);
        $this->assertEquals($linksObject, Links::createAbsoluteWithRelated($self));
    }

    public function testCreateRelativeWithRelated()
    {
        $baseUri = "http://example.com/api";
        $self = new Link("/users");
        $links = ["related" => $self];

        $linksObject = $this->createLinks($baseUri, $links);
        $this->assertEquals($linksObject, Links::createRelativeWithRelated($baseUri, $self));
    }

    public function testCreateAbsoluteWithPagination()
    {
        $uri = "http://example.com/api/users/";
        $pagination = new StubPaginationLinkProvider();

        $linksObject = $this->createLinks("")->setPagination($uri, $pagination);
        $this->assertEquals($linksObject, Links::createAbsoluteWithPagination($uri, $pagination));
    }

    public function testCreateRelativeWithPagination()
    {
        $baseUri = "http://example.com/api";
        $uri = "/users";
        $pagination = new StubPaginationLinkProvider();

        $linksObject = $this->createLinks($baseUri)->setPagination($uri, $pagination);
        $this->assertEquals($linksObject, Links::createRelativeWithPagination($baseUri, $uri, $pagination));
    }

    public function testTransform()
    {
        $self = new Link("http://example.com/api/users");
        $links = ["self" => $self, "related" => $self];

        $linksObject = $this->createLinks("", $links);
        $this->assertArrayHasKey("self", $linksObject->transform());
        $this->assertArrayHasKey("related", $linksObject->transform());
    }

    public function testGetSelfWhenEmpty()
    {
        $linksObject = $this->createLinks();
        $this->assertNull($linksObject->getSelf());
    }

    public function testGetSelfWhenNotEmpty()
    {
        $self = new Link("http://example.com/api/users");

        $linksObject = $this->createLinks()->setSelf($self);
        $this->assertEquals($self, $linksObject->getSelf());
    }

    public function testGetRelatedWhenNotEmpty()
    {
        $related = new Link("http://example.com/api/users");

        $linksObject = $this->createLinks()->setRelated($related);
        $this->assertEquals($related, $linksObject->getRelated());
    }

    public function testGetFirstWhenEmpty()
    {
        $linksObject = $this->createLinks();
        $this->assertNull($linksObject->getFirst());
    }

    public function testGetFirstWhenNotEmpty()
    {
        $first = new Link("http://example.com/api/users?page[number]=1");

        $linksObject = $this->createLinks()->setFirst($first);
        $this->assertEquals($first, $linksObject->getFirst());
    }

    public function testGetLastWhenNotEmpty()
    {
        $last = new Link("http://example.com/api/users?page[number]=10");

        $linksObject = $this->createLinks()->setLast($last);
        $this->assertEquals($last, $linksObject->getLast());
    }

    public function testGetPrevWhenNotEmpty()
    {
        $prev = new Link("http://example.com/api/users?page[number]=4");

        $linksObject = $this->createLinks()->setPrev($prev);
        $this->assertEquals($prev, $linksObject->getPrev());
    }

    public function testGetNextWhenNotEmpty()
    {
        $next = new Link("http://example.com/api/users?page[number]=6");

        $linksObject = $this->createLinks()->setNext($next);
        $this->assertEquals($next, $linksObject->getNext());
    }

    public function testSetPagination()
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

    public function testGetLink()
    {
        $self = new Link("http://example.com/api/users");

        $linksObject = $this->createLinks()->addLink("self", $self);
        $this->assertEquals($self, $linksObject->getLink("self"));
    }

    public function testGetMultipleLinks()
    {
        $self = new Link("http://example.com/api/users/1");
        $related = new Link("http://example.com/api/people/1");
        $links = ["self" => $self, "related" => $related];

        $linksObject = $this->createLinks()->addLinks($links);
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
