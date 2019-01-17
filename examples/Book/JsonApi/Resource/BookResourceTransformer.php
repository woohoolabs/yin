<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Examples\Book\JsonApi\Resource;

use WoohooLabs\Yin\JsonApi\Schema\Link\Link;
use WoohooLabs\Yin\JsonApi\Schema\Links;
use WoohooLabs\Yin\JsonApi\Schema\Relationship\ToManyRelationship;
use WoohooLabs\Yin\JsonApi\Schema\Relationship\ToOneRelationship;
use WoohooLabs\Yin\JsonApi\Schema\Resource\AbstractResource;

class BookResourceTransformer extends AbstractResource
{
    /**
     * @var AuthorResourceTransformer
     */
    private $authorTransformer;

    /**
     * @var PublisherResourceTransformer
     */
    private $publisherTransformer;

    public function __construct(
        AuthorResourceTransformer $authorTransformer,
        PublisherResourceTransformer $publisherTransformer
    ) {
        $this->authorTransformer = $authorTransformer;
        $this->publisherTransformer = $publisherTransformer;
    }

    /**
     * Provides information about the "type" member of the current resource.
     *
     * The method returns the type of the current resource.
     *
     * @param array $book
     */
    public function getType($book): string
    {
        return "books";
    }

    /**
     * Provides information about the "id" member of the current resource.
     *
     * The method returns the ID of the current resource which should be a UUID.
     *
     * @param array $book
     */
    public function getId($book): string
    {
        return (string) $book["id"];
    }

    /**
     * Provides information about the "meta" member of the current resource.
     *
     * The method returns an array of non-standard meta information about the resource. If
     * this array is empty, the member won't appear in the response.
     *
     * @param array $book
     */
    public function getMeta($book): array
    {
        return [];
    }

    /**
     * Provides information about the "links" member of the current resource.
     *
     * The method returns a new Links schema object if you want to provide linkage
     * data about the resource or null if it should be omitted from the response.
     *
     * @param array $book
     */
    public function getLinks($book): ?Links
    {
        return Links::createWithoutBaseUri(
            [
                "self" => new Link($this->getSelfLinkHref($book))
            ]
        );
    }

    public function getSelfLinkHref(array $book): string
    {
        return "/books/" . $this->getId($book);
    }

    /**
     * Provides information about the "attributes" member of the current resource.
     *
     * The method returns an array of attributes if you want the section to
     * appear in the response or null if it should be omitted. In the returned array,
     * the keys signify the attribute names, while the values are callables receiving the
     * domain object as an argument, and they should return the value of the corresponding
     * attribute.
     *
     * @param array $book
     * @return callable[]
     */
    public function getAttributes($book): array
    {
        return [
            "title" => function (array $book) {
                return $book["title"];
            },
            "isbn13" => function (array $book) {
                return $book["isbn13"];
            },
            "releaseDate" => function (array $book) {
                return $book["release_date"];
            },
            "hardCover" => function (array $book) {
                return $book["hard_cover"];
            },
            "pages" => function (array $book) {
                return (int) $book["pages"];
            },
        ];
    }

    /**
     * Returns an array of relationship names which are included in the response by default.
     *
     * @param array $book
     */
    public function getDefaultIncludedRelationships($book): array
    {
        return ["authors"];
    }

    /**
     * Provides information about the "relationships" member of the current resource.
     *
     * The method returns an array where the keys signify the relationship names,
     * while the values are callables receiving the domain object as an argument,
     * and they should return a new relationship instance (to-one or to-many).
     *
     * @param array $book
     * @return callable[]
     */
    public function getRelationships($book): array
    {
        return [
            "authors" => function (array $book) {
                return
                    ToManyRelationship::create()
                        ->setLinks(
                            new Links(
                                $this->getSelfLinkHref($book),
                                [
                                    "self" => new Link("/relationships/authors")
                                ]
                            )
                        )
                        ->setData($book["authors"], $this->authorTransformer)
                    ;
            },
            "publisher" => function ($book) {
                return
                    ToOneRelationship::create()
                        ->setLinks(
                            new Links(
                                $this->getSelfLinkHref($book),
                                [
                                    "self" => new Link("/relationships/publisher")
                                ]
                            )
                        )
                        ->setData($book["publisher"], $this->publisherTransformer)
                        ->omitWhenNotIncluded()
                    ;
            }
        ];
    }
}
