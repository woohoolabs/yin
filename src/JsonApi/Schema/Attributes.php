<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

use WoohooLabs\Yin\JsonApi\Request\Criteria;

class Attributes
{
    /**
     * @var array
     */
    private $attributes;

    /**
     * @param array $attributes
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * @param string $name
     * @param \Closure $attribute
     */
    public function setAttribute($name, \Closure $attribute)
    {
        $this->attributes[$name] = $attribute;
    }

    /**
     * @param mixed $resource
     * @param \WoohooLabs\Yin\JsonApi\Request\Criteria $criteria
     * @param string $resourceType
     * @return array
     */
    public function transform($resource, Criteria $criteria, $resourceType)
    {
        $attributes = [];

        foreach ($this->attributes as $name => $attribute) {
            if ($criteria->getIncludedFields($resourceType)) {
                $attributes[$name] = $attribute($resource, $criteria);
            }
        }

        return $attributes;
    }
}
