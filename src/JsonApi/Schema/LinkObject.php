<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema;

class LinkObject extends Link
{
    use MetaTrait;

    public function __construct(string $href, array $meta = [])
    {
        parent::__construct($href);
        $this->meta = $meta;
    }

    /**
     * @return array|mixed
     */
    public function transform(string $baseUri)
    {
        $link = ["href" => parent::transform($baseUri)];

        if (empty($this->meta) === false) {
            $link["meta"] = $this->meta;
        }

        return $link;
    }
}
