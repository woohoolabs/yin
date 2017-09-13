<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema\Data;

class CollectionData extends AbstractData
{
    public function transformPrimaryResources(): ?iterable
    {
        return array_values($this->primaryKeys);
    }
}
