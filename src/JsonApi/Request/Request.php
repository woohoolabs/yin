<?php
namespace WoohooLabs\Yin\JsonApi\Request;

use Psr\Http\Message\ServerRequestInterface;

class Request implements RequestInterface
{
    /**
     * @var array
     */
    private $queryParams = [];

    /**
     * @var array
     */
    private $attributes = [];

    /**
     * @var array
     */
    private $body;

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
     * @return $this
     */
    public static function fromRequest(ServerRequestInterface $request)
    {
        $body = json_decode($request->getBody()->getContents(), true);

        return new self(
            $request->getQueryParams(),
            $request->getAttributes(),
            is_array($body) ? $body : []
        );
    }

    /**
     * @param array $queryParams
     * @param array $attributes
     * @param array $body
     */
    public function __construct(array $queryParams, array $attributes, array $body)
    {
        $this->queryParams = $queryParams;
        $this->attributes = $attributes;
        $this->body = $body;

        $this->setIncludedRelationships();
        $this->setIncludedFields();
        $this->setSorting();
    }

    protected function setIncludedFields()
    {
        foreach ($this->getQueryParam("fields", []) as $resourceType => $fields) {
            $this->includedFields[$resourceType] = array_flip(explode(",", $fields));
        }
    }

    /**
     * @param string $resourceType
     * @return array
     */
    public function getIncludedFields($resourceType)
    {
        return isset($this->includedFields[$resourceType]) ? array_keys($this->includedFields[$resourceType]) : [];
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
        $relationshipNames = explode(",", $this->getQueryParam("include", ""));
        foreach ($relationshipNames as $relationship) {
            $relationship = ".$relationship.";
            $length = strlen($relationship);
            $dot1 = 0;

            while ($dot1 < $length - 1) {
                $dot2 = strpos($relationship, ".", $dot1 + 1);
                $path = substr($relationship, 1, $dot1 > 0 ? $dot1 - 1 : 0);
                $name = substr($relationship, $dot1 + 1, $dot2 - $dot1 - 1);

                if (isset($this->includedRelationships[$path]) === false) {
                    $this->includedRelationships[$path] = [];
                }
                $this->includedRelationships[$path][$name] = $name;

                $dot1 = $dot2;
            };
        }
    }

    /**
     * @param string $baseRelationshipPath
     * @return array
     */
    public function getIncludedRelationships($baseRelationshipPath)
    {
        if (isset($this->includedRelationships[$baseRelationshipPath])) {
            return $this->includedRelationships[$baseRelationshipPath];
        } else {
            return [];
        }
    }

    /**
     * @param string $baseRelationshipPath
     * @param string $relationshipName
     * @return bool
     */
    public function isIncludedRelationship($baseRelationshipPath, $relationshipName)
    {
        return isset($this->includedRelationships[$baseRelationshipPath][$relationshipName]);
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
        $this->pagination = $this->getQueryParam("page", null);
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
        $this->filtering = $this->getQueryParam("filter", null);
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
     * @return mixed
     */
    public function getAttribute($name, $default = null)
    {
        return isset($this->attributes[$name]) ? $this->attributes[$name] : $default;
    }

    /**
     * @param string $name
     * @param mixed $default
     * @return array|string
     */
    public function getQueryParam($name, $default = null)
    {
        return isset($this->queryParams[$name]) ? $this->queryParams[$name] : $default;
    }
}
