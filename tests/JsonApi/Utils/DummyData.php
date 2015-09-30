<?php
namespace WoohooLabsTest\Yin\JsonApi\Utils;

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
