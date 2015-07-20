<?php
namespace WoohooLabs\Yin\JsonApi\Request;

class RelationshipRequest implements RequestInterface
{
    /**
     * @var \WoohooLabs\Yin\JsonApi\Request\RequestInterface
     */
    private $request;

    /**
     * @var string
     */
    private $resourceType;

    /**
     * @var string
     */
    private $relationshipName;

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param string $resourceType
     * @param string $relationshipName
     */
    public function __construct(RequestInterface $request, $resourceType, $relationshipName)
    {
        $this->request = $request;
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
     * @return bool
     */
    public function isIncludedRelationship($baseRelationshipPath, $relationshipName)
    {
        return false;
    }

    /**
     * @return array
     */
    public function getSorting()
    {
        return $this->request->getSorting();
    }

    /**
     * @return array|null
     */
    public function getPagination()
    {
        return $this->request->getPagination();
    }

    /**
     * @return array
     */
    public function getFiltering()
    {
        return $this->request->getPagination();
    }

    /**
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function getAttribute($name, $default = null)
    {
        return $this->request->getAttribute($name, $default);
    }

    /**
     * @param string $name
     * @param mixed $default
     * @return array|string
     */
    public function getQueryParam($name, $default = null)
    {
        return $this->request->getQueryParam($name, $default);
    }
}
