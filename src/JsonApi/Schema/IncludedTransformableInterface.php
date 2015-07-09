<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

use WoohooLabs\Yin\JsonApi\Request\Criteria;

interface IncludedTransformableInterface
{
    /**
     * @param \WoohooLabs\Yin\JsonApi\Schema\Included $included
     * @param \WoohooLabs\Yin\JsonApi\Request\Criteria $criteria
     * @return array
     */
    public function transform(Included $included, Criteria $criteria);
}
