<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

use WoohooLabs\Yin\JsonApi\Request\Criteria;

class LinkObject extends Link
{
    use MetaTrait;

    /**
     * @param string $href
     * @param array $meta
     */
    public function __construct($href, array $meta = [])
    {
       parent::__construct($href);
        $this->meta = $meta;
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\Criteria $criteria
     * @return array
     */
    public function transform(Criteria $criteria)
    {
        $link = parent::transform($criteria);
        $this->addTransformedMetaToArray($link);

        return $link;
    }
}
