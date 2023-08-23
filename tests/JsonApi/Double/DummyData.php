<?php

declare(strict_types=1);

namespace Devleand\Yin\Tests\JsonApi\Double;

use Devleand\Yin\JsonApi\Schema\Data\AbstractData;

class DummyData extends AbstractData
{
    public function transformPrimaryData(): ?iterable
    {
        return [];
    }
}
