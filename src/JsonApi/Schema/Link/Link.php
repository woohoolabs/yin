<?php

declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema\Link;

class Link
{
    /**
     * @var string
     */
    private $href;

    public function __construct(string $href)
    {
        $this->href = $href;
    }

    public function getHref(): string
    {
        return $this->href;
    }

    /**
     * @internal
     * @return string|mixed
     */
    public function transform(string $baseUri)
    {
        return $baseUri . $this->href;
    }
}
