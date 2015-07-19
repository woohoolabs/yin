<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

use WoohooLabs\Yin\JsonApi\Request\Request;

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
     * @param \WoohooLabs\Yin\JsonApi\Request\Request $request
     * @param \WoohooLabs\Yin\JsonApi\Schema\Included $included
     * @param string $resourceType
     * @param string $baseRelationshipPath
     * @return array
     */
    public function transform($resource, Request $request, Included $included, $resourceType, $baseRelationshipPath)
    {
        $relationships = [];

        foreach ($this->relationships as $relationshipName => $relationshipCallback) {
            /** @var \WoohooLabs\Yin\JsonApi\Schema\AbstractRelationship $relationship */
            $relationship = $relationshipCallback($resource, $baseRelationshipPath);

            $relationships[$relationshipName] = $relationship->transform(
                $request,
                $included,
                $resourceType,
                $baseRelationshipPath,
                $relationshipName
            );
        }

        return $relationships;
    }
}
