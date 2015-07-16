<?php
namespace WoohooLabs\Yin\JsonApi\Transformer;

use WoohooLabs\Yin\JsonApi\Request\Criteria;
use WoohooLabs\Yin\JsonApi\Schema\Included;

interface ResourceTransformerInterface
{
    /**
     * @param mixed $resource
     * @return array|null
     */
    public function transformToResourceIdentifier($resource);

    /**
     * @param mixed $resource
     * @param \WoohooLabs\Yin\JsonApi\Request\Criteria $criteria
     * @param \WoohooLabs\Yin\JsonApi\Schema\Included $included
     * @param string $relationshipPath
     * @return array|null
     */
    public function transformToResource($resource, Criteria $criteria, Included $included, $relationshipPath = "");
}
