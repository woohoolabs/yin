<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

use WoohooLabs\Yin\JsonApi\Request\Criteria;
use WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformerInterface;

abstract class AbstractRelationship
{
    use LinksTrait;
    use MetaTrait;

    abstract protected function addResource(array $resourceIdentifier);

    /**
     * @param mixed $resource
     * @param \WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformerInterface $transformer
     * @param \WoohooLabs\Yin\JsonApi\Request\Criteria $criteria
     * @param \WoohooLabs\Yin\JsonApi\Schema\Included $includes
     * @param string $relationshipPath
     */
    public function includeRelationship(
        $resource,
        ResourceTransformerInterface $transformer,
        Criteria $criteria,
        Included $includes,
        $relationshipPath
    ) {
        $this->addResource($transformer->transformToResourceIdentifier($resource));
        if ($criteria->isIncludedRelationship($relationshipPath)) {
            $includes->addIncludedResource(
                $transformer->transformToResource(
                    $resource,
                    $criteria,
                    $includes,
                    $relationshipPath
                )
            );
        }
    }
}
