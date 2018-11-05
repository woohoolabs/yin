<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema\Link;

abstract class AbstractLinks
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
    public function __construct(string $baseUri = "", array $links = [])
    {
        $this->baseUri = $baseUri;
        $this->links = $links;
    }

    public function getBaseUri(): string
    {
        return $this->baseUri;
    }

    public function getLink(string $name): ?Link
    {
        return $this->links[$name] ?? null;
    }

    protected function addLink(string $name, ?Link $link): void
    {
        $this->links[$name] = $link;
    }

    /**
     * @internal
     */
    public function transform(): array
    {
        $links = [];

        foreach ($this->links as $rel => $link) {
            $links[$rel] = $links[$rel] ? $link->transform($this->baseUri) : null;
        }

        return $links;
    }
}
