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
     * @param string $relationshipPath
     * @return array
     */
    public function transform($resource, Criteria $criteria, Included $included, $relationshipPath)
    {
        $relationships = [];

        foreach ($this->relationships as $rel => $relationship) {
            if ($criteria->isIncludedRelationship($relationshipPath . "." . $rel)) {
                $relationships[$rel] = $relationship($resource, $criteria, $included);
            }
        }

        return $relationships;
    }
}
