<?php
namespace Src\JsonApi\Resource;

use WoohooLabs\Yin\JsonApi\Schema\Attributes;
use WoohooLabs\Yin\JsonApi\Schema\OneToManyIterableRelationship;
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
        return new Attributes(
            [
                "title" => function($resource) { return $resource["title"]; },
            ]
        );
    }

    /**
     * @param mixed $resource
     * @return \WoohooLabs\Yin\JsonApi\Schema\Relationships
     */
    protected function getRelationships($resource)
    {
        return new Relationships([
            "authors" => function($resource) {
                return new OneToManyIterableRelationship($resource["authors"], $this->authorTransformer);
            },
            "publisher" => function($resource) {
                return new OneToOneRelationship($resource["publisher"], $this->publisherTransformer);
            }
        ]);
    }
}
