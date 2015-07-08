<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

class Included implements Transformable
{
    /**
     * @var array
     */
    private $included;

    /**
     * @param string $type
     * @param string $id
     * @param array $resource
     * @return $this
     */
    public function addIncludedResource($type, $id, array $resource)
    {
        if (isset($this->included[$type]) === false) {
            $this->included[$type] = [];
        }

        $this->included[$type][$id] = $resource;

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
