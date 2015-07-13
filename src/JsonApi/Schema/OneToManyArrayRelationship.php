<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

use WoohooLabs\Yin\JsonApi\Request\Criteria;
use WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformerInterface;

class OneToManyArrayRelationship extends AbstractRelationship
{
    /**
     * @param array $data
     * @param \WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformerInterface $resourceTransformer
     */
    public function __construct(array $data, ResourceTransformerInterface $resourceTransformer)
    {
        parent::__construct($data, $resourceTransformer);
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\Criteria $criteria
     * @param \WoohooLabs\Yin\JsonApi\Schema\Included $includes
     * @param string $relationshipPath
     * @return array
     */
    protected function transformData(Criteria $criteria, Included $includes, $relationshipPath)
    {
        $result = [];

        foreach ($this->data as $item) {
            $result[] = $this->transformResource($item, $criteria, $includes, $relationshipPath);
        }

        return $result;
    }
}
