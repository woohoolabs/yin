<?php
namespace WoohooLabs\Yin\JsonApi\Transformer;

use WoohooLabs\Yin\JsonApi\Request\Criteria;
use WoohooLabs\Yin\JsonApi\Schema\Link;

interface ResourceTransformerInterface
{
    /**
     * @param mixed $resource
     * @return array
     */
    public function transformToResourceIdentifier($resource);

    /**
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractCompoundDocument $document
     * @param mixed $resource
     * @param \WoohooLabs\Yin\JsonApi\Request\Criteria $criteria
     * @param string $relationshipPath
     * @return array
     */
    public function transformToResource(
        AbstractCompoundDocument $document,
        $resource,
        Criteria $criteria,
        $relationshipPath = ""
    );
}
