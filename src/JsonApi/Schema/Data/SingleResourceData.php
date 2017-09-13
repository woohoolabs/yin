<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema\Data;

class SingleResourceData extends AbstractData
{
    public function transformPrimaryResources(): ?iterable
    {
        if ($this->hasPrimaryResources() === false) {
            return null;
        }

        reset($this->primaryKeys);
        $key = key($this->primaryKeys);

        return $this->resources[$key];
    }
}
