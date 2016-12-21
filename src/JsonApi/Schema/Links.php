<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

use WoohooLabs\Yin\JsonApi\Schema\Pagination\PaginationLinkProviderInterface;

class Links
{
    /**
     * @var string
     */
    protected $baseUri;

    /**
     * @var array
     */
    protected $links;

    /**
     * @param \WoohooLabs\Yin\JsonApi\Schema\Link[] $links
     * @return $this
     */
    public static function createWithoutBaseUri(array $links = [])
    {
        return new self("", $links);
    }

    /**
     * @param string $baseUri
     * @param \WoohooLabs\Yin\JsonApi\Schema\Link[] $links
     * @return $this
     */
    public static function createWithBaseUri($baseUri, array $links = [])
    {
        return new self($baseUri, $links);
    }

    /**
     * @param string $baseUri
     * @param \WoohooLabs\Yin\JsonApi\Schema\Link[] $links
     */
    public function __construct($baseUri = "", array $links = [])
    {
        $this->baseUri = $baseUri;
        $this->links = $links;
    }

    /**
     * @return array
     */
    public function transform()
    {
        $links = [];

        foreach ($this->links as $rel => $link) {
            /** @var \WoohooLabs\Yin\JsonApi\Schema\Link $link */
            $links[$rel] = $link ? $link->transform($this->baseUri) : null;
        }

        return $links;
    }

    /**
     * @return string
     */
    public function getBaseUri()
    {
        return $this->baseUri;
    }

    /**
     * @param string $baseUri
     * @return $this
     */
    public function setBaseUri($baseUri)
    {
        $this->baseUri = $baseUri;

        return $this;
    }

    /**
     * @return \WoohooLabs\Yin\JsonApi\Schema\Link|null
     */
    public function getSelf()
    {
        return $this->getLink("self");
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Schema\Link $self
     * @return $this
     */
    public function setSelf(Link $self = null)
    {
        $this->links["self"] = $self;

        return $this;
    }

    /**
     * @return \WoohooLabs\Yin\JsonApi\Schema\Link|null
     */
    public function getRelated()
    {
        return $this->getLink("related");
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Schema\Link $related
     * @return $this
     */
    public function setRelated(Link $related = null)
    {
        $this->links["related"] = $related;

        return $this;
    }

    /**
     * @return \WoohooLabs\Yin\JsonApi\Schema\Link|null
     */
    public function getFirst()
    {
        return $this->getLink("first");
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Schema\Link|null $first
     * @return $this
     */
    public function setFirst(Link $first = null)
    {
        $this->links["first"] = $first;

        return $this;
    }

    /**
     * @return \WoohooLabs\Yin\JsonApi\Schema\Link|null
     */
    public function getLast()
    {
        return $this->getLink("last");
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Schema\Link|null $last
     * @return $this
     */
    public function setLast(Link $last = null)
    {
        $this->links["last"] = $last;

        return $this;
    }

    /**
     * @return \WoohooLabs\Yin\JsonApi\Schema\Link|null
     */
    public function getPrev()
    {
        return $this->getLink("prev");
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Schema\Link|null $prev
     * @return $this
     */
    public function setPrev(Link $prev = null)
    {
        $this->links["prev"] = $prev;

        return $this;
    }

    /**
     * @return \WoohooLabs\Yin\JsonApi\Schema\Link|null
     */
    public function getNext()
    {
        return $this->getLink("next");
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Schema\Link|null $next
     * @return $this
     */
    public function setNext(Link $next = null)
    {
        $this->links["next"] = $next;

        return $this;
    }

    /**
     * @return \WoohooLabs\Yin\JsonApi\Schema\Link|null
     */
    public function getAbout()
    {
        return $this->getLink("about");
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Schema\Link|null $about
     * @return $this
     */
    public function setAbout(Link $about = null)
    {
        $this->links["about"] = $about;

        return $this;
    }

    /**
     * @param string $uri
     * @param \WoohooLabs\Yin\JsonApi\Schema\Pagination\PaginationLinkProviderInterface $paginationProvider
     * @return $this
     */
    public function setPagination($uri, PaginationLinkProviderInterface $paginationProvider)
    {
        $this->setSelf($paginationProvider->getSelfLink($uri));
        $this->setFirst($paginationProvider->getFirstLink($uri));
        $this->setLast($paginationProvider->getLastLink($uri));
        $this->setPrev($paginationProvider->getPrevLink($uri));
        $this->setNext($paginationProvider->getNextLink($uri));

        return $this;
    }

    /**
     * @param $name
     * @return \WoohooLabs\Yin\JsonApi\Schema\Link $links
     */
    public function getLink($name)
    {
        return isset($this->links[$name]) ? $this->links[$name] : null;
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Schema\Link[] $links
     * @return $this
     */
    public function setLinks(array $links)
    {
        foreach ($links as $rel => $link) {
            $this->setLink($rel, $link);
        }

        return $this;
    }

    /**
     * @param string $name
     * @param \WoohooLabs\Yin\JsonApi\Schema\Link $link
     * @return $this
     */
    public function setLink($name, Link $link = null)
    {
        $this->links[$name] = $link;

        return $this;
    }
}
