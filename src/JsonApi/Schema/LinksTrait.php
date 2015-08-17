<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

trait LinksTrait
{
    /**
     * @var \WoohooLabs\Yin\JsonApi\Schema\Links
     */
    protected $links;

    /**
     * @return \WoohooLabs\Yin\JsonApi\Schema\Links
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Schema\Links $links
     * @return $this
     */
    public function setLinks(Links $links)
    {
        $this->links = $links;

        return $this;
    }
}
