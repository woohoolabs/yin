<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

trait MetaTrait
{
    /**
     * @var array
     */
    private $meta;

    /**
     * @param array $meta
     * @return $this
     */
    public function setMeta(array $meta)
    {
        $this->meta = $meta;

        return $this;
    }

    /**
     * @param array $array
     */
    public function addTransformedMetaToArray(array &$array)
    {
        if (empty($this->meta) === false) {
            $array["meta"] = $this->meta;
        }
    }
}
