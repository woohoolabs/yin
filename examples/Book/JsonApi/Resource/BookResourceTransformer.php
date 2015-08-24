<?php
namespace WoohooLabs\Yin\Examples\Book\JsonApi\Resource;

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
     * @var \WoohooLabs\Yin\Examples\Book\JsonApi\Resource\AuthorResourceTransformer
     */
    private $authorTransformer;

    /**
     * @var \WoohooLabs\Yin\Examples\Book\JsonApi\Resource\PublisherResourceTransformer
     */
    private $publisherTransformer;

    /**
     * @param \WoohooLabs\Yin\Examples\Book\JsonApi\Resource\AuthorResourceTransformer $authorTransformer
     * @param \WoohooLabs\Yin\Examples\Book\JsonApi\Resource\PublisherResourceTransformer $publisherTransformer
     */
    public function __construct(
        AuthorResourceTransformer $authorTransformer,
        PublisherResourceTransformer $publisherTransformer
    ) {
        $this->authorTransformer = $authorTransformer;
        $this->publisherTransformer = $publisherTransformer;
    }

    /**
     * @param array $book
     * @return string
     */
    public function getType($book)
    {
        return "book";
    }

    /**
     * @param array $book
     * @return string
     */
    public function getId($book)
    {
        return $book["id"];
    }

    /**
     * @param array $book
     * @return array
     */
    public function getMeta($book)
    {
        return [];
    }

    /**
     * @param array $book
     * @return \WoohooLabs\Yin\JsonApi\Schema\Links|null
     */
    public function getLinks($book)
    {
        return null;
    }

    /**
     * @param array $book
     * @return \WoohooLabs\Yin\JsonApi\Schema\Attributes
     */
    public function getAttributes($book)
    {
        return new Attributes(
            [
                "title" => function(array $book) { return $book["title"]; },
                "pages" => function(array $book) { return $this->toInt($book["pages"]); },
            ]
        );
    }

    /**
     * @param array $book
     * @return \WoohooLabs\Yin\JsonApi\Schema\Relationships|null
     */
    public function getRelationships($book)
    {
        return new Relationships(
            [
                "authors" => function(array $book) {
                    return ToManyRelationship::create()
                        ->setLinks(
                            new Links(
                                [
                                    "self" => new Link("http://example.com/api/books/relationships/authors")
                                ]
                            )
                        )
                        ->setData($book["authors"], $this->authorTransformer);
                },
                "publisher" => function($book) {
                    return ToOneRelationship::create()
                        ->setLinks(
                            new Links(
                                [
                                    "self" => new Link("http://example.com/api/books/relationships/publisher")
                                ]
                            )
                        )
                        ->setData($book["publisher"], $this->publisherTransformer);
                }
            ]
        );
    }
}
