<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

class Link implements Transformable
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
    public function transform()
    {
        return $this->href;
    }
}
