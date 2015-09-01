<?php
namespace WoohooLabs\Yin\Examples\Book\JsonApi\Resource;

use WoohooLabs\Yin\JsonApi\Transformer\AbstractResourceTransformer;

class PublisherResourceTransformer extends AbstractResourceTransformer
{
    /**
     * Provides information about the "type" section of the current resource.
     *
     * The method returns the type of the current resource.
     *
     * @param array $publisher
     * @return string
     */
    public function getType($publisher)
    {
        return "publisher";
    }

    /**
     * Provides information about the "id" section of the current resource.
     *
     * The method returns the ID of the current resource which should be a UUID.
     *
     * @param array $publisher
     * @return string
     */
    public function getId($publisher)
    {
        return $publisher["id"];
    }

    /**
     * Provides information about the "meta" section of the current resource.
     *
     * The method returns an array of non-standard meta information about the resource. If
     * this array is empty, the section won't appear in the response.
     *
     * @param array $publisher
     * @return array
     */
    public function getMeta($publisher)
    {
        return [];
    }

    /**
     * Provides information about the "links" section of the current resource.
     *
     * The method returns a new Links schema object if you want to provide linkage
     * data about the resource or null if it should be omitted from the response.
     *
     * @param array $publisher
     * @return \WoohooLabs\Yin\JsonApi\Schema\Links|null
     */
    public function getLinks($publisher)
    {
        return null;
    }

    /**
     * Provides information about the "attributes" section of the current resource.
     *
     * The method returns an array where the keys signify the attribute names,
     * while the values are closures receiving the domain object as an argument,
     * and they should return the value of the corresponding attribute.
     *
     * @param array $publisher
     * @return array
     */
    public function getAttributes($publisher)
    {
        return [
            "name" => function(array $publisher) { return $publisher["name"]; },
        ];
    }

    /**
     * Returns an array of relationship names which are included in the response by default.
     *
     * @param array $publisher
     * @return array
     */
    public function getDefaultRelationships($publisher)
    {
        return [];
    }

    /**
     * Provides information about the "relationships" section of the current resource.
     *
     * The method returns an array where the keys signify the relationship names,
     * while the values are closures receiving the domain object as an argument,
     * and they should return a new relationship instance (to-one or to-many).
     *
     * @param array $publisher
     * @return array
     */
    public function getRelationships($publisher)
    {
        return [];
    }
}
