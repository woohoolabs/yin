<?php
namespace WoohooLabs\Yin\JsonApi\Request;

use Psr\Http\Message\ServerRequestInterface;

class RelationshipRequest extends Request
{
    /**
     * @var string
     */
    private $resourceType;

    /**
     * @var string
     */
    private $relationshipName;

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param string $resourceType
     * @param string $relationshipName
     */
    public function __construct(ServerRequestInterface $request, $resourceType, $relationshipName)
    {
        parent::__construct($request);
        $this->resourceType = $resourceType;
        $this->relationshipName = $relationshipName;
    }

    /**
     * @param string $resourceType
     * @return array
     */
    public function getIncludedFields($resourceType)
    {
        return $this->resourceType === $resourceType ? [$this->relationshipName] : [];
    }

    /**
     * @param string $resourceType
     * @param string $field
     * @return bool
     */
    public function isIncludedField($resourceType, $field)
    {
        return $this->resourceType === $resourceType && $this->relationshipName === $field;
    }

    /**
     * @param string $baseRelationshipPath
     * @return array
     */
    public function getIncludedRelationships($baseRelationshipPath)
    {
        return [];
    }

    /**
     * @param string $baseRelationshipPath
     * @param string $relationshipName
     * @param array $defaultRelationships
     * @return bool
     */
    public function isIncludedRelationship($baseRelationshipPath, $relationshipName, array $defaultRelationships)
    {
        return false;
    }
}
