<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

use WoohooLabs\Yin\JsonApi\Exception\InclusionUnrecognized;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;

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
     * @param string $name
     * @param \Closure $relationship
     */
    public function setRelationship($name, \Closure $relationship)
    {
        $this->relationships[$name] = $relationship;
    }

    /**
     * @param mixed $resource
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \WoohooLabs\Yin\JsonApi\Schema\Included $included
     * @param string $resourceType
     * @param string $baseRelationshipPath
     * @return array
     */
    public function transform(
        $resource,
        RequestInterface $request,
        Included $included,
        $resourceType,
        $baseRelationshipPath
    ) {
        $relationships = [];

        $this->validateRelationships($request, $baseRelationshipPath, $relationships);

        foreach ($this->relationships as $relationshipName => $relationshipCallback) {
            $relationship = $this->transformRelationship(
                $relationshipName,
                $resource,
                $request,
                $included,
                $resourceType,
                $baseRelationshipPath
            );

            if ($relationship !== null) {
                $relationships[$relationshipName] = $relationship;
            }
        }

        return $relationships;
    }

    /**
     * @param string $relationshipName
     * @param mixed $resource
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \WoohooLabs\Yin\JsonApi\Schema\Included $included
     * @param string $resourceType
     * @param string $baseRelationshipPath
     * @return array|null
     */
    public function transformRelationship(
        $relationshipName,
        $resource,
        RequestInterface $request,
        Included $included,
        $resourceType,
        $baseRelationshipPath
    ) {
        if (isset($this->relationships[$relationshipName]) === false) {
            return null;
        }

        $relationshipCallback = $this->relationships[$relationshipName];
        /** @var \WoohooLabs\Yin\JsonApi\Schema\AbstractRelationship $relationship */
        $relationship = $relationshipCallback($resource);

        return $relationship->transform(
            $request,
            $included,
            $resourceType,
            $baseRelationshipPath,
            $relationshipName
        );
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param string $baseRelationshipPath
     * @param array $relationships
     * @throws \WoohooLabs\Yin\JsonApi\Exception\InclusionUnrecognized
     */
    protected function validateRelationships(RequestInterface $request, $baseRelationshipPath, array $relationships)
    {
        $requestedRelationships = $request->getIncludedRelationships($baseRelationshipPath);

        $nonExistentRelationships = array_diff($requestedRelationships, array_keys($relationships));
        if (empty($nonExistentRelationships) === false) {
            foreach ($nonExistentRelationships as &$relationship) {
                $relationship = ($baseRelationshipPath ? $baseRelationshipPath . "." : "") . $relationship;
            }

            throw new InclusionUnrecognized($nonExistentRelationships);
        }
    }
}
