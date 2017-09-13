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
     * @var Link[]
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

    public function getSelf(): ?Link
    {
        return $this->getLink("self");
    }

    public function setSelf(?Link $self): Links
    {
        $this->links["self"] = $self;

        return $this;
    }

    public function getRelated(): ?Link
    {
        return $this->getLink("related");
    }

    public function setRelated(?Link $related): Links
    {
        $this->links["related"] = $related;

        return $this;
    }

    public function getFirst(): ?Link
    {
        return $this->getLink("first");
    }

    public function setFirst(?Link $first): Links
    {
        $this->links["first"] = $first;

        return $this;
    }

    public function getLast(): ?Link
    {
        return $this->getLink("last");
    }

    public function setLast(?Link $last): Links
    {
        $this->links["last"] = $last;

        return $this;
    }

    public function getPrev(): ?Link
    {
        return $this->getLink("prev");
    }

    public function setPrev(?Link $prev): Links
    {
        $this->links["prev"] = $prev;

        return $this;
    }

    public function getNext(): ?Link
    {
        return $this->getLink("next");
    }

    public function setNext(?Link $next): Links
    {
        $this->links["next"] = $next;

        return $this;
    }

    public function getAbout(): ?Link
    {
        return $this->getLink("about");
    }

    public function setAbout(?Link $about): Links
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

    public function getLink(string $name): ?Link
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

    public function setLink(string $name, ?Link $link): Links
    {
        $this->links[$name] = $link;

        return $this;
    }
}
