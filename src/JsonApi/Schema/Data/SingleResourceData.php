<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema\Data;

use Traversable;

class SingleResourceData extends AbstractData
{
    /**
     * @return array|Traversable|null
     */
    public function transformPrimaryResources()
    {
        if ($this->hasPrimaryResources() === false) {
            return null;
        }

        reset($this->primaryKeys);
        $key = key($this->primaryKeys);

        return $this->resources[$key];
    }
}
