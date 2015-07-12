<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

interface SimpleTransformableInterface
{
    /**
     * @return array
     */
    public function transform();
}
