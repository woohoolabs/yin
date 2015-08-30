<?php
namespace WoohooLabs\Yin\Examples\Book\JsonApi\Resource;

use WoohooLabs\Yin\JsonApi\Schema\Attributes;
use WoohooLabs\Yin\JsonApi\Schema\Link;
use WoohooLabs\Yin\JsonApi\Schema\Links;
use WoohooLabs\Yin\JsonApi\Schema\RelativeLinks;
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
     * Provides information about the "type" section of the current resource.
     *
     * The method returns the type of the current resource.
     *
     * @param array $book
     * @return string
     */
    public function getType($book)
    {
        return "book";
    }

    /**
     * Provides information about the "id" section of the current resource.
     *
     * The method returns the ID of the current resource which should be a UUID.
     *
     * @param array $book
     * @return string
     */
    public function getId($book)
    {
        return $book["id"];
    }

    /**
     * Provides information about the "meta" section of the current resource.
     *
     * The method returns an array of non-standard meta information about the resource. If
     * this array is empty, the section won't appear in the response.
     *
     * @param array $book
     * @return array
     */
    public function getMeta($book)
    {
        return [];
    }

    /**
     * Provides information about the "links" section of the current resource.
     *
     * The method returns a new Links schema object if you want to provide linkage
     * data about the resource or null if it should be omitted from the response.
     *
     * @param array $book
     * @return \WoohooLabs\Yin\JsonApi\Schema\Links|null
     */
    public function getLinks($book)
    {
        return new Links(
            [
                "self" => new Link($this->getSelfLinkHref($book))
            ]
        );
    }

    /**
     * @param array $book
     * @return string
     */
    public function getSelfLinkHref(array $book)
    {
        return "http://example.com/api/books/" . $this->getId($book);
    }

    /**
     * Provides information about the "attributes" section of the current resource.
     *
     * The method returns a new Attributes schema object if you want the section to
     * appear in the response of null if it should be omitted.
     *
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
     * Provides information about the "relationships" section of the current resource.
     *
     * The method returns a new Relationships schema object if you want the section to
     * appear in the response of null if it should be omitted.
     *
     * @param array $book
     * @return \WoohooLabs\Yin\JsonApi\Schema\Relationships|null
     */
    public function getRelationships($book)
    {
        return new Relationships(
            [
                "authors" => function(array $book) {
                    return ToManyRelationship::create()
                        ->setLinks(new RelativeLinks(
                            $this->getSelfLinkHref($book),
                            [
                                "self" => new Link("/relationships/authors")
                            ]
                        ))
                        ->setData($book["authors"], $this->authorTransformer);
                },
                "publisher" => function($book) {
                    return ToOneRelationship::create()
                        ->setLinks(new RelativeLinks(
                            $this->getSelfLinkHref($book),
                            [
                                "self" => new Link("/relationships/publisher")
                            ]
                        ))
                        ->setData($book["publisher"], $this->publisherTransformer);
                }
            ]
        );
    }
}
