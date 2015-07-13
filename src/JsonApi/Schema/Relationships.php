<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

use WoohooLabs\Yin\JsonApi\Request\Criteria;

class Relationships
{
    /**
     * @var array
     */
    private $relationships;

    /**
     * @param array $relationships
     */
    public function __construct(array $relationships = [])
    {
        $this->relationships = $relationships;
    }

    /**
     * @param array $relationships
     */
    public function setRelationships(array $relationships)
    {
        $this->relationships = $relationships;
    }

    /**
     * @param string $rel
     * @param \Closure $relationship
     */
    public function setRelationship($rel, \Closure $relationship)
    {
        $this->relationships[$rel] = $relationship;
    }

    /**
     * @param mixed $resource
     * @param \WoohooLabs\Yin\JsonApi\Request\Criteria $criteria
     * @param \WoohooLabs\Yin\JsonApi\Schema\Included $included
     * @param string $baseRelationshipPath
     * @return array
     */
    public function transform(
        $resource,
        Criteria $criteria,
        Included $included,
        $baseRelationshipPath
    ) {
        $relationships = [];

        foreach ($this->relationships as $rel => $relationshipCallback) {
            $relationshipPath = ($baseRelationshipPath ? "$baseRelationshipPath." : "") . $rel;
            if ($criteria->isIncludedRelationship($relationshipPath)) {
                /** @var \WoohooLabs\Yin\JsonApi\Schema\AbstractRelationship $relationship */
                $relationship = $relationshipCallback($resource);
                $relationships[$rel] = $relationship->transform($criteria, $included, $relationshipPath);
            }
        }

        return $relationships;
    }
}
