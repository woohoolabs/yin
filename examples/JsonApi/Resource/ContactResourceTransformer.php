<?php
namespace WoohooLabs\Yin\Examples\JsonApi\Resource;

use WoohooLabs\Yin\JsonApi\Schema\Attributes;
use WoohooLabs\Yin\JsonApi\Schema\Link;
use WoohooLabs\Yin\JsonApi\Schema\Links;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractResourceTransformer;

class ContactResourceTransformer extends AbstractResourceTransformer
{
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
        return new Links(
            [
                "self" => new Link("http://example.com/api/contacts/" . $this->getId($resource))
            ]
        );
    }

    /**
     * @param mixed $resource
     * @return \WoohooLabs\Yin\JsonApi\Schema\Attributes|null
     */
    public function getAttributes($resource)
    {
        return new Attributes(
            [
                $resource["type"] => function($resource) { return $resource["value"]; },
            ]
        );
    }

    /**
     * @param mixed $resource
     * @return \WoohooLabs\Yin\JsonApi\Schema\Relationships|null
     */
    public function getRelationships($resource)
    {
        return null;
    }
}
