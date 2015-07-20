<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

use WoohooLabs\Yin\JsonApi\Request\RequestInterface;

interface TransformableInterface
{
    /**
     * @param mixed $resource
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @return array
     */
    public function transform($resource, RequestInterface $request);
}
