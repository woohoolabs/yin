<?php

declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema\Link;

use WoohooLabs\Yin\JsonApi\Schema\Pagination\PaginationLinkProviderInterface;

use function array_values;

class DocumentLinks extends AbstractLinks
{
    /** @var Link[] */
    protected array $profiles = [];

    /**
     * @param Link[] $links
     * @param Link[] $profiles
     */
    public static function createWithoutBaseUri(array $links = [], array $profiles = []): DocumentLinks
    {
        return new DocumentLinks("", $links, $profiles);
    }

    /**
     * @param Link[] $links
     * @param Link[] $profiles
     */
    public static function createWithBaseUri(string $baseUri, array $links = [], array $profiles = []): DocumentLinks
    {
        return new DocumentLinks($baseUri, $links, $profiles);
    }

    /**
     * @param Link[] $links
     * @param Link[] $profiles
     */
    public function __construct(string $baseUri = "", array $links = [], array $profiles = [])
    {
        parent::__construct($baseUri, $links);
        foreach ($profiles as $profile) {
            $this->addProfile($profile);
        }
    }

    public function setBaseUri(string $baseUri): DocumentLinks
    {
        $this->baseUri = $baseUri;

        return $this;
    }

    public function getSelf(): ?Link
    {
        return $this->getLink("self");
    }

    public function setSelf(?Link $self): DocumentLinks
    {
        $this->addLink("self", $self);

        return $this;
    }

    public function getRelated(): ?Link
    {
        return $this->getLink("related");
    }

    public function setRelated(?Link $related): DocumentLinks
    {
        $this->addLink("related", $related);

        return $this;
    }

    public function getFirst(): ?Link
    {
        return $this->getLink("first");
    }

    public function setFirst(?Link $first): DocumentLinks
    {
        $this->addLink("first", $first);

        return $this;
    }

    public function getLast(): ?Link
    {
        return $this->getLink("last");
    }

    public function setLast(?Link $last): DocumentLinks
    {
        $this->addLink("last", $last);

        return $this;
    }

    public function getPrev(): ?Link
    {
        return $this->getLink("prev");
    }

    public function setPrev(?Link $prev): DocumentLinks
    {
        $this->addLink("prev", $prev);

        return $this;
    }

    public function getNext(): ?Link
    {
        return $this->getLink("next");
    }

    public function setNext(?Link $next): DocumentLinks
    {
        $this->addLink("next", $next);

        return $this;
    }

    /**
     * @return Link[]
     */
    public function getProfiles(): array
    {
        return array_values($this->profiles);
    }

    public function addProfile(Link $profile): DocumentLinks
    {
        $this->profiles[$profile->getHref()] = $profile;

        return $this;
    }

    public function setPagination(string $uri, PaginationLinkProviderInterface $paginationProvider, string $queryString = ""): DocumentLinks
    {
        $this->setSelf($paginationProvider->getSelfLink($uri, $queryString));
        $this->setFirst($paginationProvider->getFirstLink($uri, $queryString));
        $this->setLast($paginationProvider->getLastLink($uri, $queryString));
        $this->setPrev($paginationProvider->getPrevLink($uri, $queryString));
        $this->setNext($paginationProvider->getNextLink($uri, $queryString));

        return $this;
    }
}
