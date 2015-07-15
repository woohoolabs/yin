<?php
namespace WoohooLabs\Yin\Examples\JsonApi\Resource;

use WoohooLabs\Yin\JsonApi\Schema\Attributes;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractResourceTransformer;

class PublisherResourceTransformer extends AbstractResourceTransformer
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
        return "publisher";
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
     * @return \WoohooLabs\Yin\JsonApi\Schema\Attributes|null
     */
    protected function getAttributes($resource)
    {
        return new Attributes([
            "name" => function($resource) { return $resource["name"]; },
        ]);
    }

    /**
     * @param mixed $resource
     * @return \WoohooLabs\Yin\JsonApi\Schema\Relationships|null
     */
    protected function getRelationships($resource)
    {
        return null;
    }
}
