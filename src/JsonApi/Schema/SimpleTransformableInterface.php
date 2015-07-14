<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

interface SimpleTransformableInterface
{
    /**
     * @return array|string
     */
    public function transform();
}
