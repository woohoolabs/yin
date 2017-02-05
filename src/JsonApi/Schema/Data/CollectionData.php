<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema\Data;

use Traversable;

class CollectionData extends AbstractData
{
    /**
     * @return array|Traversable|null
     */
    public function transformPrimaryResources()
    {
        return array_values($this->primaryKeys);
    }
}
