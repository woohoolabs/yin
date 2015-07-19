<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

use WoohooLabs\Yin\JsonApi\Request\Request;
use WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformerInterface;

class OneToOneRelationship extends AbstractRelationship
{
    /**
     * @param mixed $data
     * @param \WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformerInterface $resourceTransformer
     */
    public function __construct($data, ResourceTransformerInterface $resourceTransformer)
    {
        parent::__construct($data, $resourceTransformer);
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\Request $request
     * @param \WoohooLabs\Yin\JsonApi\Schema\Included $included
     * @param string $baseRelationshipPath
     * @param string $relationshipName
     * @return array
     */
    protected function transformData(Request $request, Included $included, $baseRelationshipPath, $relationshipName)
    {
        if ($this->data !== null) {
            return $this->transformResource(
                $this->data,
                $request,
                $included,
                $baseRelationshipPath,
                $relationshipName
            );
        } else {
            return null;
        }
    }
}
