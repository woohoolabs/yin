<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

use WoohooLabs\Yin\JsonApi\Request\Criteria;

class Included implements TransformableInterface
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
     * @param mixed $resource
     * @param \WoohooLabs\Yin\JsonApi\Request\Criteria $criteria
     * @return array
     */
    public function transform($resource, Criteria $criteria)
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
