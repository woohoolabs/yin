<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

use WoohooLabs\Yin\JsonApi\Request\Request;

class Attributes
{
    /**
     * @var array
     */
    private $attributes;

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

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
     * @param \WoohooLabs\Yin\JsonApi\Request\Request $request
     * @param string $resourceType
     * @return array
     */
    public function transform($resource, Request $request, $resourceType)
    {
        $attributes = [];

        foreach ($this->attributes as $name => $attribute) {
            if ($request->isIncludedField($resourceType, $name)) {
                $attributes[$name] = $attribute($resource, $request);
            }
        }

        return $attributes;
    }
}
