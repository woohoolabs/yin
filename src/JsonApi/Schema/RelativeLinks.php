<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

class RelativeLinks extends Links
{
    /**
     * @param array $links
     */
    public function __construct(array $links)
    {
        parent::__construct("", $links);
    }
}
