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
    public function transformAbsolute()
    {
        return $this->transformRelative("");
    }

    /**
     * @param string $baseUri
     * @return string
     */
    public function transformRelative($baseUri)
    {
        $link = ["href" => parent::transformRelative($baseUri)];

        if (empty($this->meta) === false) {
            $link["meta"] = $this->meta;
        }

        return $link;
    }
}
