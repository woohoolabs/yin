<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

use WoohooLabs\Yin\JsonApi\Request\Criteria;

abstract class Attributes implements TransformableInterface
{
    /**
     * @var array
     */
    private $attributes;

    /**
     * @param string $rel
     * @param \Closure $attribute
     */
    public function setAttribute($rel, \Closure $attribute)
    {
        $this->attributes[$rel] = $attribute;
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\Criteria $criteria
     * @return array
     */
    public function transform(Criteria $criteria)
    {
        $attributes = [];

        foreach ($this->attributes as $name => $attribute) {
            $attributes[$name] = $attribute($criteria);
        }

        return $attributes;
    }
}
