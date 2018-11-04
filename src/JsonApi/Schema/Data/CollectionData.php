<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema\Data;

class CollectionData extends AbstractData
{
    public function transformPrimaryData(): ?iterable
    {
        return array_values($this->primaryKeys);
    }
}
