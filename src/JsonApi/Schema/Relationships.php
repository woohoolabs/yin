<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

use WoohooLabs\Yin\JsonApi\Request\Criteria;

abstract class Relationships implements IncludedTransformableInterface
{
    /**
     * @var array
     */
    private $relationships;

    /**
     * @param string $rel
     * @param \Closure $relationship
     */
    public function setRelationship($rel, \Closure $relationship)
    {
        $this->relationships[$rel] = $relationship;
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Schema\Included $included
     * @param \WoohooLabs\Yin\JsonApi\Request\Criteria $criteria
     * @return array
     */
    public function transform(Included $included, Criteria $criteria)
    {
        $relationships = [];

        foreach ($this->relationships as $rel => $relationship) {
            $relationships[$rel] = $relationship($included, $criteria);
        }

        return $relationships;
    }
}
