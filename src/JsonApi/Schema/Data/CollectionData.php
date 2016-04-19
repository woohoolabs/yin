<?php
namespace WoohooLabs\Yin\JsonApi\Schema\Data;

class CollectionData extends AbstractData
{
    /**
     * @return \Traversable|array|null
     */
    public function transformPrimaryResources()
    {
        return array_values($this->primaryKeys);
    }
}
