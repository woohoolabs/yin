<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

interface Transformable
{
    /**
     * @return array
     */
    public function transform();
}
