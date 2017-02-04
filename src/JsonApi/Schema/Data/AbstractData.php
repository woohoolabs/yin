<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema\Data;

abstract class AbstractData implements DataInterface
{
    /**
     * @var array
     */
    protected $resources = [];

    /**
     * @var array
     */
    protected $primaryKeys = [];

    /**
     * @var array
     */
    protected $includedKeys = [];

    /**
     * @param string $type
     * @param string $id
     * @return array|null
     */
    public function getResource($type, $id)
    {
        return isset($this->resources[$type . "." . $id]) ? $this->resources[$type . "." . $id] : null;
    }

    /**
     * @return bool
     */
    public function hasPrimaryResources()
    {
        return empty($this->primaryKeys) === false;
    }

    /**
     * @param string $type
     * @param string $id
     * @return bool
     */
    public function hasPrimaryResource($type, $id)
    {
        return isset($this->primaryKeys[$type . "." . $id]);
    }

    /**
     * @return bool
     */
    public function hasIncludedResources()
    {
        return empty($this->includedKeys) === false;
    }

    /**
     * @param string $type
     * @param string $id
     * @return bool
     */
    public function hasIncludedResource($type, $id)
    {
        return isset($this->includedKeys[$type . "." . $id]);
    }

    /**
     * @param \Traversable|array $transformedResources
     * @return $this
     */
    public function setPrimaryResources($transformedResources)
    {
        $this->primaryKeys = [];
        foreach ($transformedResources as $resource) {
            $this->addPrimaryResource($resource);
        }

        return $this;
    }

    /**
     * @param array $transformedResource
     * @return $this
     */
    public function addPrimaryResource(array $transformedResource = [])
    {
        $type = $transformedResource["type"];
        $id = $transformedResource["id"];
        if ($this->hasIncludedResource($type, $id) === true) {
            unset($this->includedKeys[$type . "." . $id]);
        }

        $this->addResourceToPrimaryData($transformedResource);

        return $this;
    }

    /**
     * @param \Traversable|array $transformedResources
     * @return $this
     */
    public function setIncludedResources($transformedResources)
    {
        $this->includedKeys = [];
        foreach ($transformedResources as $resource) {
            $this->addIncludedResource($resource);
        }

        return $this;
    }

    /**
     * @param array $transformedResource
     * @return $this
     */
    public function addIncludedResource(array $transformedResource)
    {
        if ($this->hasPrimaryResource($transformedResource["type"], $transformedResource["id"]) === false) {
            $this->addResourceToIncludedData($transformedResource);
        }

        return $this;
    }

    /**
     * @return \Traversable|array
     */
    public function transformIncludedResources()
    {
        return array_values($this->includedKeys);
    }

    protected function addResourceToPrimaryData(array $transformedResource)
    {
        $type = $transformedResource["type"];
        $id = $transformedResource["id"];

        $this->resources[$type . "." . $id] = $transformedResource;
        $this->primaryKeys[$type. "." . $id] = &$this->resources[$type . "." . $id];
    }

    protected function addResourceToIncludedData(array $transformedResource)
    {
        $type = $transformedResource["type"];
        $id = $transformedResource["id"];

        $this->resources[$type . "." . $id] = $transformedResource;
        $this->includedKeys[$type . "." . $id] = &$this->resources[$type . "." . $id];
    }
}
