<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

use WoohooLabs\Yin\JsonApi\Request\Criteria;
use WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformerInterface;

class OneToManyIterableRelationship extends AbstractRelationship
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
     * @param \WoohooLabs\Yin\JsonApi\Schema\Included $included
     * @param string $baseRelationshipPath
     * @param string $relationshipName
     * @return array
     */
    protected function transformData(Criteria $criteria, Included $included, $baseRelationshipPath, $relationshipName)
    {
        $result = [];

        if ($this->data !== null) {
            foreach ($this->data as $item) {
                $result[] = $this->transformResource($item, $criteria, $included, $baseRelationshipPath, $relationshipName);
            }
        }

        return $result;
    }
}
