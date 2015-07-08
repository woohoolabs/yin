<?php
namespace WoohooLabs\Yin\JsonApi\Transformer;

use WoohooLabs\Yin\JsonApi\Request\Criteria;
use WoohooLabs\Yin\JsonApi\Schema\Link;

interface ResourceTransformerInterface
{
    /**
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractDocument $document
     * @param mixed $resource
     * @param \WoohooLabs\Yin\JsonApi\Request\Criteria $criteria
     * @param \WoohooLabs\Yin\JsonApi\Schema\Link $selfLink
     * @param \WoohooLabs\Yin\JsonApi\Schema\Link $relatedLink
     * @return array
     */
    public function transform(
        AbstractDocument $document,
        $resource, Criteria
        $criteria,
        Link $selfLink = null,
        Link $relatedLink = null
    );
}
