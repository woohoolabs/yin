<?php
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
        return isset($this->resources[$type][$id]) ? $this->resources[$type][$id] : null;
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
        return isset($this->primaryKeys[$type][$id]);
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
        return isset($this->includedKeys[$type][$id]);
    }

    /**
     * @param array $resources
     * @return $this
     */
    public function setPrimaryResources($resources)
    {
        $this->primaryKeys = [];
        foreach ($resources as $resource) {
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
            unset($this->includedKeys[$type][$id]);
            $this->primaryKeys[$type][$id] = true;
        } else {
            $this->addResource($this->primaryKeys, $transformedResource);
        }

        return $this;
    }

    /**
     * @param array $resources
     * @return $this
     */
    public function setIncludedResources($resources)
    {
        $this->includedKeys = [];
        foreach ($resources as $resource) {
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
            $this->addResource($this->includedKeys, $transformedResource);
        }

        return $this;
    }

    /**
     * @return \Traversable|array
     */
    public function transformIncludedResources()
    {
        ksort($this->includedKeys);

        $result = [];
        foreach ($this->includedKeys as $type => $ids) {
            ksort($ids);
            foreach ($ids as $id => $value) {
                $result[] = $this->resources[$type][$id];
            }
        }

        return $result;
    }

    protected function addResource(&$keys, array $transformedResource)
    {
        $type = $transformedResource["type"];
        $id = $transformedResource["id"];

        $keys[$type][$id] = true;
        $this->resources[$type][$id] = $transformedResource;
    }
}
