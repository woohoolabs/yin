<?php
namespace WoohooLabs\Yin\Examples\Book\JsonApi\Resource;

use WoohooLabs\Yin\JsonApi\Schema\Attributes;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractResourceTransformer;

class PublisherResourceTransformer extends AbstractResourceTransformer
{
    /**
     * @param array $publisher
     * @return string
     */
    public function getType($publisher)
    {
        return "publisher";
    }

    /**
     * @param array $publisher
     * @return string
     */
    public function getId($publisher)
    {
        return $publisher["id"];
    }

    /**
     * @param array $publisher
     * @return array
     */
    public function getMeta($publisher)
    {
        return [];
    }

    /**
     * @param array $publisher
     * @return \WoohooLabs\Yin\JsonApi\Schema\Links|null
     */
    public function getLinks($publisher)
    {
        return null;
    }

    /**
     * @param array $publisher
     * @return \WoohooLabs\Yin\JsonApi\Schema\Attributes|null
     */
    public function getAttributes($publisher)
    {
        return new Attributes([
            "name" => function(array $publisher) { return $publisher["name"]; },
        ]);
    }

    /**
     * @param array $publisher
     * @return \WoohooLabs\Yin\JsonApi\Schema\Relationships|null
     */
    public function getRelationships($publisher)
    {
        return null;
    }
}
