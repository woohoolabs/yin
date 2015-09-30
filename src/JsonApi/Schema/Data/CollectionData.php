<?php
namespace WoohooLabs\Yin\JsonApi\Schema\Data;

class CollectionData extends AbstractData
{
    /**
     * @return \Traversable|array|null
     */
    public function transformPrimaryResources()
    {
        if ($this->hasPrimaryResources() === false) {
            return [];
        }

        ksort($this->primaryKeys);

        $result = [];
        foreach ($this->primaryKeys as $type => $ids) {
            ksort($ids);
            foreach ($ids as $id => $value) {
                $result[] = $this->resources[$type][$id];
            }
        }

        return $result;
    }
}
