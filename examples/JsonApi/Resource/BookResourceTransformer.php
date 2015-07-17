<?php
namespace WoohooLabs\Yin\Examples\JsonApi\Resource;

use WoohooLabs\Yin\JsonApi\Schema\Attributes;
use WoohooLabs\Yin\JsonApi\Schema\OneToManyTraversableRelationship;
use WoohooLabs\Yin\JsonApi\Schema\OneToOneRelationship;
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
     * @param string $relationshipPath
     * @return \WoohooLabs\Yin\JsonApi\Schema\Links|null
     */
    public function getLinks($resource, $relationshipPath)
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
     * @return \WoohooLabs\Yin\JsonApi\Schema\Relationships
     */
    public function getRelationships($resource)
    {
        return new Relationships(
            [
                "authors" => function($resource) {
                    return new OneToManyTraversableRelationship($resource["authors"], $this->authorTransformer);
                },
                "publisher" => function($resource) {
                    return new OneToOneRelationship($resource["publisher"], $this->publisherTransformer);
                }
            ]
        );
    }
}
