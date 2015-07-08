<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

trait LinksTrait
{
    /**
     * @var \WoohooLabs\Yin\JsonApi\Schema\Links
     */
    private $links;

    /**
     * @param \WoohooLabs\Yin\JsonApi\Schema\Links $links
     * @return $this
     */
    public function setLinks(Links $links)
    {
        $this->links = $links;

        return $this;
    }

    /**
     * @param array $array
     */
    public function addTransformedLinksToArray(array &$array)
    {
        if ($this->links !== null) {
            $array["links"] = $this->links->transform();
        }
    }
}
