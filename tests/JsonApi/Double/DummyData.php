<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Double;

use WoohooLabs\Yin\JsonApi\Schema\Data\AbstractData;

class DummyData extends AbstractData
{
    /**
     * @inheritDoc
     */
    public function transformPrimaryResources()
    {
        return [];
    }
}
