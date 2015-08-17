<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

trait MetaTrait
{
    /**
     * @var array
     */
    protected $meta = [];


    /**
     * @return array
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * @param array $meta
     * @return $this
     */
    public function setMeta(array $meta)
    {
        $this->meta = $meta;

        return $this;
    }
}
