<?php
namespace WoohooLabs\Yin\JsonApi\Request;

use Psr\Http\Message\ServerRequestInterface;

class Criteria
{
    /**
     * @var array
     */
    private $queryParams;

    /**
     * @var array
     */
    private $includedFields = [];

    /**
     * @var array
     */
    private $includedRelationships = [];

    /**
     * @var array
     */
    private $sorting = [];

    /**
     * @var array
     */
    private $pagination = [];

    /**
     * @var array
     */
    private $filtering = [];

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     */
    public function __construct(ServerRequestInterface $request)
    {
        $this->queryParams = $request->getQueryParams();

        $this->setIncludedRelationships();
        $this->setIncludedFields();
        $this->setSorting();
    }

    protected function setIncludedFields()
    {
        foreach ($this->getQueryParam("fields", []) as $resourceType => $fields) {
            $this->includedFields[$resourceType] = explode(",", $fields);
        }
    }

    /**
     * @param string $resourceType
     * @return array
     */
    public function getIncludedFields($resourceType)
    {
        return isset($this->includedFields[$resourceType]) ? $this->includedFields[$resourceType] : [];
    }

    /**
     * @param string $resourceType
     * @param string $field
     * @return bool
     */
    public function isIncludedField($resourceType, $field)
    {
        return isset($this->includedFields[$resourceType][$field]);
    }

    /**
     * @return array
     */
    protected function setIncludedRelationships()
    {
        $this->includedRelationships = array_flip(explode(",", $this->getQueryParam("include", "")));
    }

    /**
     * @param $relationshipPath
     * @return bool
     */
    public function isIncludedRelationship($relationshipPath)
    {
        // TODO linkage of intermediate resources
        // TODO direct relationships with - separation
        return isset($this->includedRelationships[$relationshipPath]);
    }

    protected function setSorting()
    {
        $this->sorting = explode(",", $this->getQueryParam("sort", ""));
    }

    /**
     * @return array
     */
    public function getSorting()
    {
        return $this->sorting;
    }

    protected function setPagination()
    {
        $this->pagination = isset($this->queryParams["page"]) ? $this->queryParams["page"] : null;
    }

    /**
     * @return array|null
     */
    public function getPagination()
    {
        return $this->pagination;
    }

    protected function setFiltering()
    {
        $this->filtering = isset($this->queryParams["filter"]) ? $this->queryParams["filter"] : null;
    }

    /**
     * @return array
     */
    public function getFiltering()
    {
        return $this->filtering;
    }

    /**
     * @param string $name
     * @param mixed $default
     * @return array|string
     */
    protected function getQueryParam($name, $default)
    {
        return isset($this->queryParams[$name]) ? $this->queryParams[$name] : $default;
    }
}
