<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

use WoohooLabs\Yin\JsonApi\Request\Criteria;

class Included implements TransformableInterface
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
     * @param \WoohooLabs\Yin\JsonApi\Request\Criteria $criteria
     * @return array
     */
    public function transform(Criteria $criteria)
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
