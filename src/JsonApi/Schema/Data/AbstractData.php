<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema\Data;

use Traversable;

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
     * @return array|null
     */
    public function getResource(string $type, string $id)
    {
        return isset($this->resources[$type . "." . $id]) ? $this->resources[$type . "." . $id] : null;
    }

    public function hasPrimaryResources(): bool
    {
        return empty($this->primaryKeys) === false;
    }

    public function hasPrimaryResource(string $type, string $id): bool
    {
        return isset($this->primaryKeys[$type . "." . $id]);
    }

    public function hasIncludedResources(): bool
    {
        return empty($this->includedKeys) === false;
    }

    public function hasIncludedResource(string $type, string $id): bool
    {
        return isset($this->includedKeys[$type . "." . $id]);
    }

    /**
     * @param array|Traversable $transformedResources
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
     * @return $this
     */
    public function addPrimaryResource(array $transformedResource = [])
    {
        $type = $transformedResource["type"];
        $id = $transformedResource["id"];
        if ($this->hasIncludedResource($type, $id)) {
            unset($this->includedKeys[$type . "." . $id]);
        }

        $this->addResourceToPrimaryData($transformedResource);

        return $this;
    }

    /**
     * @param array|Traversable $transformedResources
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
     * @return array|Traversable
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
