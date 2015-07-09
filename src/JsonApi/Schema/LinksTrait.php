<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

use WoohooLabs\Yin\JsonApi\Request\Criteria;

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
     * @param \WoohooLabs\Yin\JsonApi\Request\Criteria $criteria
     * @param array $array
     */
    public function addTransformedLinksToArray(array &$array, Criteria $criteria)
    {
        if ($this->links !== null) {
            $array["links"] = $this->links->transform($criteria);
        }
    }
}
