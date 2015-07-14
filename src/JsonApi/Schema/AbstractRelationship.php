<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

use WoohooLabs\Yin\JsonApi\Request\Criteria;
use WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformerInterface;

abstract class AbstractRelationship
{
    use LinksTrait;
    use MetaTrait;

    /**
     * @var mixed
     */
    protected $data;

    /**
     * @var \WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformerInterface
     */
    protected $resourceTransformer;

    /**
     * @param mixed $data
     * @param \WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformerInterface $resourceTransformer
     */
    public function __construct($data, ResourceTransformerInterface $resourceTransformer)
    {
        $this->data = $data;
        $this->resourceTransformer = $resourceTransformer;
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\Criteria $criteria
     * @param \WoohooLabs\Yin\JsonApi\Schema\Included $included
     * @param string $baseRelationshipPath
     * @param string $relationshipName
     * @return array
     */
    abstract protected function transformData(
        Criteria $criteria,
        Included $included,
        $baseRelationshipPath,
        $relationshipName
    );

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\Criteria $criteria
     * @param \WoohooLabs\Yin\JsonApi\Schema\Included $included
     * @param string $resourceType
     * @param string $baseRelationshipPath
     * @param string $relationshipName
     * @return array|null
     */
    public function transform(
        Criteria $criteria,
        Included $included,
        $resourceType,
        $baseRelationshipPath,
        $relationshipName
    ) {
        $relationship = null;

        $data = $this->transformData($criteria, $included, $baseRelationshipPath, $relationshipName);

        if ($criteria->isIncludedField($resourceType, $relationshipName)) {
            $relationship = [];

            // LINKS
            if ($this->links !== null) {
                $relationship["links"] = $this->links->transform();
            }

            // META
            if (empty($this->meta) === false) {
                $relationship["meta"] = $this->meta;
            }

            // DATA
            if ($data !== null) {
                $relationship["data"] = $data;
            }
        }

        return $relationship;
    }

    /**
     * @param mixed $resource
     * @param \WoohooLabs\Yin\JsonApi\Request\Criteria $criteria
     * @param \WoohooLabs\Yin\JsonApi\Schema\Included $included
     * @param string $baseRelationshipPath
     * @param string $relationshipName
     * @return array
     */
    protected function transformResource(
        $resource,
        Criteria $criteria,
        Included $included,
        $baseRelationshipPath,
        $relationshipName
    ) {
        if ($criteria->isIncludedRelationship($baseRelationshipPath, $relationshipName)) {
            $included->addIncludedResource(
                $this->resourceTransformer->transformToResource(
                    $resource,
                    $criteria,
                    $included,
                    $baseRelationshipPath
                )
            );
        }

        return $this->resourceTransformer->transformToResourceIdentifier($resource);
    }
}
