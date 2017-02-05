<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema;

use WoohooLabs\Yin\JsonApi\Schema\Pagination\PaginationLinkProviderInterface;

class Links
{
    /**
     * @var string
     */
    protected $baseUri;

    /**
     * @var Links[]
     */
    protected $links;

    /**
     * @param Link[] $links
     */
    public static function createWithoutBaseUri(array $links = []): Links
    {
        return new self("", $links);
    }

    /**
     * @param Link[] $links
     */
    public static function createWithBaseUri(string $baseUri, array $links = []): Links
    {
        return new self($baseUri, $links);
    }

    /**
     * @param Link[] $links
     */
    public function __construct(string $baseUri = "", array $links = [])
    {
        $this->baseUri = $baseUri;
        $this->links = $links;
    }

    public function transform(): array
    {
        $links = [];

        foreach ($this->links as $rel => $link) {
            /** @var Link $link */
            $links[$rel] = $link ? $link->transform($this->baseUri) : null;
        }

        return $links;
    }

    public function getBaseUri(): string
    {
        return $this->baseUri;
    }

    public function setBaseUri(string $baseUri): Links
    {
        $this->baseUri = $baseUri;

        return $this;
    }

    /**
     * @return Link|null
     */
    public function getSelf()
    {
        return $this->getLink("self");
    }

    public function setSelf(Link $self = null): Links
    {
        $this->links["self"] = $self;

        return $this;
    }

    /**
     * @return Link|null
     */
    public function getRelated()
    {
        return $this->getLink("related");
    }

    public function setRelated(Link $related = null): Links
    {
        $this->links["related"] = $related;

        return $this;
    }

    /**
     * @return Link|null
     */
    public function getFirst()
    {
        return $this->getLink("first");
    }

    public function setFirst(Link $first = null): Links
    {
        $this->links["first"] = $first;

        return $this;
    }

    /**
     * @return Link|null
     */
    public function getLast()
    {
        return $this->getLink("last");
    }

    public function setLast(Link $last = null): Links
    {
        $this->links["last"] = $last;

        return $this;
    }

    /**
     * @return Link|null
     */
    public function getPrev()
    {
        return $this->getLink("prev");
    }

    public function setPrev(Link $prev = null): Links
    {
        $this->links["prev"] = $prev;

        return $this;
    }

    /**
     * @return Link|null
     */
    public function getNext()
    {
        return $this->getLink("next");
    }

    public function setNext(Link $next = null): Links
    {
        $this->links["next"] = $next;

        return $this;
    }

    /**
     * @return Link|null
     */
    public function getAbout()
    {
        return $this->getLink("about");
    }

    public function setAbout(Link $about = null): Links
    {
        $this->links["about"] = $about;

        return $this;
    }

    public function setPagination(string $uri, PaginationLinkProviderInterface $paginationProvider): Links
    {
        $this->setSelf($paginationProvider->getSelfLink($uri));
        $this->setFirst($paginationProvider->getFirstLink($uri));
        $this->setLast($paginationProvider->getLastLink($uri));
        $this->setPrev($paginationProvider->getPrevLink($uri));
        $this->setNext($paginationProvider->getNextLink($uri));

        return $this;
    }

    /**
     * @return Link|null
     */
    public function getLink(string $name)
    {
        return $this->links[$name] ?? null;
    }

    /**
     * @param Link[] $links
     */
    public function setLinks(array $links): Links
    {
        foreach ($links as $rel => $link) {
            $this->setLink($rel, $link);
        }

        return $this;
    }

    public function setLink(string $name, Link $link = null): Links
    {
        $this->links[$name] = $link;

        return $this;
    }
}
