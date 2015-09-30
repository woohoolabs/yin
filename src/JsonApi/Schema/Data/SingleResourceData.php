<?php
namespace WoohooLabs\Yin\JsonApi\Schema\Data;

class SingleResourceData extends AbstractData
{
    /**
     * @return \Traversable|array|null
     */
    public function transformPrimaryResources()
    {
        if ($this->hasPrimaryResources() === false) {
            return null;
        }

        $ids = reset($this->primaryKeys);
        $key = key($this->primaryKeys);
        $id = reset($ids);

        return $this->resources[$key][$id];
    }
}
