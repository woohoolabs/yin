<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema\Link;

use WoohooLabs\Yin\JsonApi\Schema\Pagination\PaginationLinkProviderInterface;

class Links extends AbstractLinks
{
    /**
     * @var Link[]
     */
    protected $profiles;

    /**
     * @param Link[] $links
     * @param Link[] $profile
     */
    public static function createWithoutBaseUri(array $links = [], array $profile = []): Links
    {
        return new self("", $links, $profile);
    }

    /**
     * @param Link[] $links
     * @param Link[] $profile
     */
    public static function createWithBaseUri(string $baseUri, array $links = [], array $profile = []): Links
    {
        return new self($baseUri, $links, $profile);
    }

    /**
     * @param Link[] $links
     * @param Link[] $profile
     */
    public function __construct(string $baseUri = "", array $links = [], array $profile = [])
    {
        parent::__construct($baseUri, $links);
        $this->profiles = $profile;
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
        $this->addLink("self", $self);

        return $this;
    }

    public function getRelated(): ?Link
    {
        return $this->getLink("related");
    }

    public function setRelated(?Link $related): Links
    {
        $this->addLink("related", $related);

        return $this;
    }

    public function getFirst(): ?Link
    {
        return $this->getLink("first");
    }

    public function setFirst(?Link $first): Links
    {
        $this->addLink("first", $first);

        return $this;
    }

    public function getLast(): ?Link
    {
        return $this->getLink("last");
    }

    public function setLast(?Link $last): Links
    {
        $this->addLink("last", $last);

        return $this;
    }

    public function getPrev(): ?Link
    {
        return $this->getLink("prev");
    }

    public function setPrev(?Link $prev): Links
    {
        $this->addLink("prev", $prev);

        return $this;
    }

    public function getNext(): ?Link
    {
        return $this->getLink("next");
    }

    public function setNext(?Link $next): Links
    {
        $this->addLink("next", $next);

        return $this;
    }

    /**
     * @return Link[]
     */
    public function getProfile(): array
    {
        return $this->profiles;
    }

    public function addProfile(?Link $profile): Links
    {
        $this->profiles[] = $profile;

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
     * @param Link[] $links
     */
    public function setLinks(array $links): Links
    {
        foreach ($links as $rel => $link) {
            $this->addLink($rel, $link);
        }

        return $this;
    }

    public function setLink(string $name, ?Link $link): Links
    {
        $this->addLink($name, $link);

        return $this;
    }
}
