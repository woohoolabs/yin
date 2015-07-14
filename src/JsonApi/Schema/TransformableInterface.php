<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

use WoohooLabs\Yin\JsonApi\Request\Criteria;

interface TransformableInterface
{
    /**
     * @param mixed $resource
     * @param \WoohooLabs\Yin\JsonApi\Request\Criteria $criteria
     * @return array|string
     */
    public function transform($resource, Criteria $criteria);
}
