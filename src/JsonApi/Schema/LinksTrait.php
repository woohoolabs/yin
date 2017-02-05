<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema;

trait LinksTrait
{
    /**
     * @var Links
     */
    protected $links;

    public function getLinks(): Links
    {
        return $this->links;
    }

    /**
     * @return $this
     */
    public function setLinks(Links $links)
    {
        $this->links = $links;

        return $this;
    }
}
