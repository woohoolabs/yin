<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

class Included
{
    /**
     * @var array
     */
    private $included = [];

    /**
     * @param array $transformedResource
     * @return $this
     */
    public function addIncludedResource(array $transformedResource)
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

        foreach ($this->included as $types) {
            ksort($types);
            foreach ($types as $item) {
                $included[] = $item;
            }
        }

        return $included;
    }
}
