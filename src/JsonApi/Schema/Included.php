<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

class Included
{
    /**
     * @var array
     */
    private $included = [];

    /**
     * @param string $type
     * @param string $id
     * @return array|null
     */
    public function getResource($type, $id)
    {
        return isset($this->included[$type][$id]) ? $this->included[$type][$id] : null;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->included);
    }

    /**
     * @param array $resources
     * @return $this
     */
    public function setResources(array $resources)
    {
        foreach ($resources as $resource) {
            $this->addResource($resource);
        }

        return $this;
    }

    /**
     * @param array $transformedResource
     * @return $this
     */
    public function addResource(array $transformedResource)
    {
        if (isset($this->included[$transformedResource["type"]]) === false) {
            $this->included[$transformedResource["type"]] = [];
        }

        $this->included[$transformedResource["type"]][$transformedResource["id"]] = $transformedResource;

        return $this;
    }

    /**
     * @return array
     */
    public function transform()
    {
        $included = [];

        ksort($this->included);
        foreach ($this->included as $id) {
            ksort($id);
            foreach ($id as $item) {
                $included[] = $item;
            }
        }

        return $included;
    }
}
