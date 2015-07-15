<?php
namespace WoohooLabs\Yin\Examples\JsonApi\Resource;

use WoohooLabs\Yin\JsonApi\Schema\Attributes;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractResourceTransformer;

class ContactResourceTransformer extends AbstractResourceTransformer
{
    public function __construct()
    {
    }

    /**
     * @param mixed $resource
     * @return string
     */
    public function getType($resource)
    {
        return "contact";
    }

    /**
     * @param mixed $resource
     * @return string
     */
    public function getId($resource)
    {
        return $resource["id"];
    }

    /**
     * @param mixed $resource
     * @return array
     */
    protected function getMeta($resource)
    {
        return [];
    }

    /**
     * @param mixed $resource
     * @param string $relationshipPath
     * @return \WoohooLabs\Yin\JsonApi\Schema\Links|null
     */
    protected function getLinks($resource, $relationshipPath)
    {
        return null;
    }

    /**
     * @param mixed $resource
     * @return \WoohooLabs\Yin\JsonApi\Schema\Attributes
     */
    protected function getAttributes($resource)
    {
        $value = $resource["type"] == "phone" ? "phone_number" : "email";

        return new Attributes([
            $value => function($resource) { return $resource["value"]; },
        ]);
    }

    /**
     * @param mixed $resource
     * @return \WoohooLabs\Yin\JsonApi\Schema\Relationships
     */
    protected function getRelationships($resource)
    {
        return null;
    }
}
