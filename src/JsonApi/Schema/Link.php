<?php
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
     * @return string
     */
    public function transformAbsolute()
    {
        return $this->transformRelative("");
    }

    /**
     * @param string $baseUri
     * @return string
     */
    public function transformRelative($baseUri)
    {
        return $baseUri . $this->href;
    }
}
