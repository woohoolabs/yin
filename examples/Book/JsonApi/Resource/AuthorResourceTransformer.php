<?php
namespace WoohooLabs\Yin\Examples\Book\JsonApi\Resource;

use WoohooLabs\Yin\JsonApi\Schema\Attributes;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractResourceTransformer;

class AuthorResourceTransformer extends AbstractResourceTransformer
{
    /**
     * @param array $author
     * @return string
     */
    public function getType($author)
    {
        return "author";
    }

    /**
     * @param array $author
     * @return string
     */
    public function getId($author)
    {
        return $author["id"];
    }

    /**
     * @param array $author
     * @return array
     */
    public function getMeta($author)
    {
        return [];
    }

    /**
     * @param array $author
     * @return \WoohooLabs\Yin\JsonApi\Schema\Links|null
     */
    public function getLinks($author)
    {
        return null;
    }

    /**
     * @param array $author
     * @return \WoohooLabs\Yin\JsonApi\Schema\Attributes|null
     */
    public function getAttributes($author)
    {
        return new Attributes(
            [
                "name" => function(array $author) { return $author["name"]; },
            ]
        );
    }

    /**
     * @param array $author
     * @return \WoohooLabs\Yin\JsonApi\Schema\Relationships|null
     */
    public function getRelationships($author)
    {
        return null;
    }
}
