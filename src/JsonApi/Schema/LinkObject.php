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
     * @param string $baseUri
     * @return array
     */
    public function transform($baseUri)
    {
        $link = ["href" => parent::transform($baseUri)];

        if (empty($this->meta) === false) {
            $link["meta"] = $this->meta;
        }

        return $link;
    }
}
