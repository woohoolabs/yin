<?php

declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema\Link;

use function array_values;

class ErrorLinks extends AbstractLinks
{
    /** @var Link[] */
    protected array $types;

    /**
     * @param Link[] $types
     */
    public static function createWithoutBaseUri(?Link $about = null, array $types = []): ErrorLinks
    {
        return new ErrorLinks("", $about, $types);
    }

    /**
     * @param Link[] $types
     */
    public static function createWithBaseUri(string $baseUri, ?Link $about = null, array $types = []): ErrorLinks
    {
        return new ErrorLinks($baseUri, $about, $types);
    }

    /**
     * @param Link[] $types
     */
    public function __construct(string $baseUri = "", ?Link $about = null, array $types = [])
    {
        parent::__construct($baseUri, ["about" => $about]);
        $this->types = $types;
    }

    public function setBaseUri(string $baseUri): ErrorLinks
    {
        $this->baseUri = $baseUri;

        return $this;
    }

    public function getAbout(): ?Link
    {
        return $this->getLink("about");
    }

    public function setAbout(?Link $about): ErrorLinks
    {
        $this->addLink("about", $about);

        return $this;
    }

    /**
     * @return Link[]
     */
    public function getTypes(): array
    {
        return array_values($this->types);
    }

    /**
     * @param Link[] $types
     */
    public function setTypes(array $types): ErrorLinks
    {
        foreach ($types as $type) {
            $this->addType($type);
        }

        return $this;
    }

    public function addType(Link $type): ErrorLinks
    {
        $this->types[$type->getHref()] = $type;

        return $this;
    }

    /**
     * @internal
     */
    public function transform(): array
    {
        $links = parent::transform();

        foreach ($this->types as $link) {
            $links["type"][] = $link->transform($this->baseUri);
        }

        return $links;
    }
}
