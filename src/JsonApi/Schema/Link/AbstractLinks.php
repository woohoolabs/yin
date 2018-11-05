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
     * @param Link[]|null[] $links
     */
    public function __construct(string $baseUri = "", array $links = [])
    {
        $this->baseUri = $baseUri;

        foreach ($links as $name => $link) {
            if ($link !== null) {
                $this->addLink($name, $link);
            }
        }
    }

    public function getBaseUri(): string
    {
        return $this->baseUri;
    }

    public function getLink(string $name): ?Link
    {
        return $this->links[$name] ?? null;
    }

    /**
     * @internal
     */
    public function transform(): array
    {
        $links = [];

        foreach ($this->links as $rel => $link) {
            $links[$rel] = $link !== null ? $link->transform($this->baseUri) : null;
        }

        return $links;
    }

    protected function addLink(string $name, ?Link $link): void
    {
        $this->links[$name] = $link;
    }
}
