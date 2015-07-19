<?php
namespace WoohooLabs\Yin\Examples\JsonApi\Resource;

use WoohooLabs\Yin\JsonApi\Schema\Attributes;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractResourceTransformer;

class AuthorResourceTransformer extends AbstractResourceTransformer
{
    /**
     * @param mixed $resource
     * @return string
     */
    public function getType($resource)
    {
        return "author";
    }

    /**
     * @param mixed $resource
     * @return string
     */
    public function getId($resource)
    {
        if (isset($resource["id"]) === false) { print_r($resource); exit(); }
        return $resource["id"];
    }

    /**
     * @param mixed $resource
     * @return array
     */
    public function getMeta($resource)
    {
        return [];
    }

    /**
     * @param mixed $resource
     * @return \WoohooLabs\Yin\JsonApi\Schema\Links|null
     */
    public function getLinks($resource)
    {
        return null;
    }

    /**
     * @param mixed $resource
     * @return \WoohooLabs\Yin\JsonApi\Schema\Attributes|null
     */
    public function getAttributes($resource)
    {
        return new Attributes(
            [
                "name" => function($resource) { return $resource["name"]; },
            ]
        );
    }

    /**
     * @param mixed $resource
     * @param string $baseRelationshipPath
     * @return \WoohooLabs\Yin\JsonApi\Schema\Relationships|null
     */
    public function getRelationships($resource, $baseRelationshipPath)
    {
        return null;
    }
}
