<?php
namespace WoohooLabs\Yin\Examples\JsonApi\Resource;

use WoohooLabs\Yin\JsonApi\Schema\Attributes;
use WoohooLabs\Yin\JsonApi\Schema\Link;
use WoohooLabs\Yin\JsonApi\Schema\Links;
use WoohooLabs\Yin\JsonApi\Schema\ToManyRelationship;
use WoohooLabs\Yin\JsonApi\Schema\ToOneRelationship;
use WoohooLabs\Yin\JsonApi\Schema\Relationships;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractResourceTransformer;

class BookResourceTransformer extends AbstractResourceTransformer
{
    /**
     * @var AuthorResourceTransformer
     */
    private $authorTransformer;

    /**
     * @var PublisherResourceTransformer
     */
    private $publisherTransformer;

    /**
     * @param AuthorResourceTransformer $authorTransformer
     * @param PublisherResourceTransformer $publisherTransformer
     */
    public function __construct(
        AuthorResourceTransformer $authorTransformer,
        PublisherResourceTransformer $publisherTransformer
    ) {
        $this->authorTransformer = $authorTransformer;
        $this->publisherTransformer = $publisherTransformer;
    }

    /**
     * @param mixed $resource
     * @return string
     */
    public function getType($resource)
    {
        return "book";
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
        return null;
    }

    /**
     * @param mixed $resource
     * @return \WoohooLabs\Yin\JsonApi\Schema\Attributes
     */
    public function getAttributes($resource)
    {
        return new Attributes(
            [
                "title" => function($resource) { return $resource["title"]; },
                "pages" => function($resource) { return $this->toInt($resource["pages"]); },
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
        return new Relationships(
            [
                "authors" => function($resource, $baseRelationshipPath) {
                    return ToManyRelationship::create()
                        ->setLinks(
                            Links::create(
                                [
                                    "self" => new Link($baseRelationshipPath . "/relationships/authors")
                                ]
                            )
                        )
                        ->setData($resource["authors"], $this->authorTransformer);
                },
                "publisher" => function($resource, $baseRelationshipPath) {
                    return ToOneRelationship::create()
                        ->setLinks(
                            Links::create(
                                [
                                    "self" => new Link($baseRelationshipPath . "/relationships/authors")
                                ]
                            )
                        )
                        ->setData($resource["publisher"], $this->publisherTransformer);
                }
            ]
        );
    }
}
