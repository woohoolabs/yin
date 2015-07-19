<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

use WoohooLabs\Yin\JsonApi\Request\Request;

class Link implements SimpleTransformableInterface
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
