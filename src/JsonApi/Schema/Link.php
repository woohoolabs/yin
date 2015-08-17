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
    public function transform()
    {
        return $this->href;
    }
}
