<?php

declare(strict_types=1);

namespace Devleand\Yin\JsonApi\Schema\Link;

class ResourceLinks extends AbstractLinks
{
    public static function createWithoutBaseUri(?Link $self = null): ResourceLinks
    {
        return new ResourceLinks("", $self);
    }

    public static function createWithBaseUri(string $baseUri, ?Link $self = null): ResourceLinks
    {
        return new ResourceLinks($baseUri, $self);
    }

    public function __construct(string $baseUri = "", ?Link $self = null)
    {
        parent::__construct($baseUri, ["self" => $self]);
    }

    public function setBaseUri(string $baseUri): ResourceLinks
    {
        $this->baseUri = $baseUri;

        return $this;
    }

    public function getSelf(): ?Link
    {
        return $this->getLink("self");
    }

    public function setSelf(?Link $self): ResourceLinks
    {
        $this->addLink("self", $self);

        return $this;
    }
}
