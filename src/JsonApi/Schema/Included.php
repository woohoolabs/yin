<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

class Included
{
    /**
     * @var array
     */
    private $included = [];

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->included);
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
     * @param string $type
     * @param string $id
     * @return array|null
     */
    public function getResource($type, $id)
    {
        return isset($this->included[$type][$id]) ? $this->included[$type][$id] : null;
    }

    /**
     * @return array
     */
    public function transform()
    {
        $included = [];

        foreach ($this->included as $types) {
            ksort($types);
            foreach ($types as $item) {
                $included[] = $item;
            }
        }

        return $included;
    }
}
