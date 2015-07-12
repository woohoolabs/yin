<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

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
     * @return array
     */
    public function transform()
    {
        $link = ["href" => parent::transform()];
        $this->addTransformedMetaToArray($link);

        return $link;
    }
}
