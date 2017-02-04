<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema;

class Link
{
    /**
     * @var string
     */
    private $href;

    /**
     * @param string $href
     */
    public function __construct($href)
    {
        $this->href = $href;
    }

    /**
     * @return string
     */
    public function getHref()
    {
        return $this->href;
    }

    /**
     * @param string $baseUri
     * @return string
     */
    public function transform($baseUri)
    {
        return $baseUri . $this->href;
    }
}
