<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

use WoohooLabs\Yin\JsonApi\Request\Request;

interface TransformableInterface
{
    /**
     * @param mixed $resource
     * @param \WoohooLabs\Yin\JsonApi\Request\Request $request
     * @return array
     */
    public function transform($resource, Request $request);
}
