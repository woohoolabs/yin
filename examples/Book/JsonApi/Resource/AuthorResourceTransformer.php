<?php
namespace WoohooLabs\Yin\Examples\Book\JsonApi\Resource;

use WoohooLabs\Yin\JsonApi\Transformer\AbstractResourceTransformer;

class AuthorResourceTransformer extends AbstractResourceTransformer
{
    /**
     * Provides information about the "type" section of the current resource.
     *
     * The method returns the type of the current resource.
     *
     * @param array $author
     * @return string
     */
    public function getType($author)
    {
        return "author";
    }

    /**
     * Provides information about the "id" section of the current resource.
     *
     * The method returns the ID of the current resource which should be a UUID.
     *
     * @param array $author
     * @return string
     */
    public function getId($author)
    {
        return $author["id"];
    }

    /**
     * Provides information about the "meta" section of the current resource.
     *
     * The method returns an array of non-standard meta information about the resource. If
     * this array is empty, the section won't appear in the response.
     *
     * @param array $author
     * @return array
     */
    public function getMeta($author)
    {
        return [];
    }

    /**
     * Provides information about the "links" section of the current resource.
     *
     * The method returns a new Links schema object if you want to provide linkage
     * data about the resource or null if it should be omitted from the response.
     *
     * @param array $author
     * @return \WoohooLabs\Yin\JsonApi\Schema\Links|null
     */
    public function getLinks($author)
    {
        return null;
    }

    /**
     * Provides information about the "attributes" section of the current resource.
     *
     * The method returns an array where the keys signify the attribute names,
     * while the values are callables receiving the domain object as an argument,
     * and they should return the value of the corresponding attribute.
     *
     * @param array $author
     * @return array
     */
    public function getAttributes($author)
    {
        return [
            "name" => function(array $author) { return $author["name"]; },
        ];
    }

    /**
     * Returns an array of relationship names which are included in the response by default.
     *
     * @param array $author
     * @return array
     */
    public function getDefaultIncludedRelationships($author)
    {
        return [];
    }

    /**
     * Provides information about the "relationships" section of the current resource.
     *
     * The method returns an array where the keys signify the relationship names,
     * while the values are callables receiving the domain object as an argument,
     * and they should return a new relationship instance (to-one or to-many).
     *
     * @param array $author
     * @return array
     */
    public function getRelationships($author)
    {
        return [];
    }
}
