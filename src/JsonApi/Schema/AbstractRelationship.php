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
     * @param \WoohooLabs\Yin\JsonApi\Schema\Included $includes
     * @param string $relationshipPath
     * @return array
     */
    abstract protected function transformData(Criteria $criteria, Included $includes, $relationshipPath);

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\Criteria $criteria
     * @param \WoohooLabs\Yin\JsonApi\Schema\Included $includes
     * @param string $relationshipPath
     * @return array
     */
    public function transform(Criteria $criteria, Included $includes, $relationshipPath)
    {
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
        if ($this->data !== null) {
            $relationship["data"] = $this->transformData($criteria, $includes, $relationshipPath);
        }

        return $relationship;
    }

    /**
     * @param mixed $resource
     * @param \WoohooLabs\Yin\JsonApi\Request\Criteria $criteria
     * @param \WoohooLabs\Yin\JsonApi\Schema\Included $includes
     * @param string $relationshipPath
     * @return array
     */
    protected function transformResource($resource, Criteria $criteria, Included $includes, $relationshipPath)
    {
        if ($criteria->isIncludedRelationship($relationshipPath)) {
            $includes->addIncludedResource(
                $this->resourceTransformer->transformToResource($resource, $criteria, $includes, $relationshipPath)
            );
        }

        return $this->resourceTransformer->transformToResourceIdentifier($resource);
    }
}
