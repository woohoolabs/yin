<?php
namespace WoohooLabs\Yin\Examples\Book\JsonApi\Hydrator;

use WoohooLabs\Yin\Examples\Book\Repository\BookRepository;
use WoohooLabs\Yin\Examples\Utils\Uuid;
use WoohooLabs\Yin\JsonApi\Exception\ClientGeneratedIdNotSupported;
use WoohooLabs\Yin\JsonApi\Hydrator\AbstractHydrator;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToManyRelationship;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToOneRelationship;

class BookHydator extends AbstractHydrator
{
    /**
     * @return string
     */
    protected function getAcceptedType()
    {
        return "book";
    }

    /**
     * @param string $clientGeneratedId
     * @throws \WoohooLabs\Yin\JsonApi\Exception\ClientGeneratedIdNotSupported
     * @throws \WoohooLabs\Yin\JsonApi\Exception\ClientGeneratedIdAlreadyExists
     */
    protected function validateClientGeneratedId($clientGeneratedId)
    {
        throw new ClientGeneratedIdNotSupported($clientGeneratedId);
    }

    /**
     * @return string
     */
    protected function generateId()
    {
        return Uuid::generate();
    }

    /**
     * @param array $book
     * @param string $id
     * @return mixed
     */
    protected function setId($book, $id)
    {
        $book["id"] = $id;

        return $book;
    }

    /**
     * @param array $book
     * @return array
     */
    protected function getAttributeHydrator($book)
    {
        return [
            "title" => function(array $book, $attribute, $data)  { $book["title"] = $attribute; return $book; },
            "pages" => function(array &$book, $attribute, $data) { $book["pages"] = $attribute; }
        ];
    }

    /**
     * @parm array $book
     * @return array
     */
    protected function getRelationshipHydrator($book)
    {
        return [
            "authors" => function(array $book, ToManyRelationship $authors, $data) {
                $book["authors"] = BookRepository::getAuthors($authors->getResourceIdentifierIds());

                return $book;
            },
            "publisher" => function(array &$book, ToOneRelationship $publisher, $data) {
                $book["publisher"] = BookRepository::getPublisher($publisher->getResourceIdentifier()->getId());
            }
        ];
    }
}
