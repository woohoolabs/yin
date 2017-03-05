<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Examples\Book\JsonApi\Hydrator;

use Exception;
use LogicException;
use WoohooLabs\Yin\Examples\Book\Repository\BookRepository;
use WoohooLabs\Yin\Examples\Utils\Uuid;
use WoohooLabs\Yin\JsonApi\Exception\ClientGeneratedIdAlreadyExists;
use WoohooLabs\Yin\JsonApi\Exception\ClientGeneratedIdNotSupported;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Hydrator\AbstractHydrator;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToManyRelationship;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToOneRelationship;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;

class BookHydator extends AbstractHydrator
{
    /**
     * Determines which resource type or types can be accepted by the hydrator.
     *
     * If the hydrator can only accept one type of resources, the method should
     * return a string. If it accepts more types, then it should return an array
     * of strings. When such a resource is received for hydration which can't be
     * accepted (its type doesn't match the acceptable type or types of the hydrator),
     * a ResourceTypeUnacceptable exception will be raised.
     *
     * @return string|array
     */
    protected function getAcceptedType()
    {
        return "book";
    }

    /**
     * Validates a client-generated ID.
     *
     * If the $clientGeneratedId is not a valid ID for the domain object, then
     * the appropriate exception should be thrown: if it is not well-formed then
     * a ClientGeneratedIdNotSupported exception can be raised, if the ID already
     * exists then a ClientGeneratedIdAlreadyExists exception can be thrown.
     *
     * @throws ClientGeneratedIdNotSupported
     * @throws ClientGeneratedIdAlreadyExists
     * @throws Exception
     */
    protected function validateClientGeneratedId(
        string $clientGeneratedId,
        RequestInterface $request,
        ExceptionFactoryInterface $exceptionFactory
    ) {
        if ($clientGeneratedId !== null) {
            throw $exceptionFactory->createClientGeneratedIdNotSupportedException($request, $clientGeneratedId);
        }
    }

    /**
     * Produces a new ID for the domain objects.
     *
     * UUID-s are preferred according to the JSON API specification.
     */
    protected function generateId(): string
    {
        return Uuid::generate();
    }

    /**
     * Sets the given ID for the domain object.
     *
     * The method mutates the domain object and sets the given ID for it.
     * If it is an immutable object or an array the whole, updated domain
     * object can be returned.
     *
     * @param array $book
     * @return mixed|void
     */
    protected function setId($book, string $id)
    {
        $book["id"] = $id;

        return $book;
    }

    /**
     * Validates the request - you can check for example if all the required attributes are present.
     *
     * @return void
     * @throws Exception
     */
    protected function validateRequest(RequestInterface $request, ExceptionFactoryInterface $exceptionFactory)
    {
        if ($request->getAttribute("title") === null) {
            throw new LogicException("The 'title' attribute is required!");
        }
    }

    /**
     * Provides the attribute hydrators.
     *
     * The method returns an array of attribute hydrators, where a hydrator is a key-value pair:
     * the key is the specific attribute name which comes from the request and the value is a
     * callable which hydrates the given attribute.
     * These callables receive the domain object (which will be hydrated), the value of the
     * currently processed attribute, the "data" part of the request and the name of the attribute
     * to be hydrated as their arguments, and they should mutate the state of the domain object.
     * If it is an immutable object or an array (and passing by reference isn't used),
     * the callable should return the domain object.
     *
     * @param array $book
     * @return callable[]
     */
    protected function getAttributeHydrator($book): array
    {
        return [
            "title" => function (array $book, $attribute, $data) {
                $book["title"] = $attribute;
                return $book;
            },
            "pages" => function (array &$book, $attribute, $data) {
                $book["pages"] = $attribute;
            }
        ];
    }

    /**
     * Provides the relationship hydrators.
     *
     * The method returns an array of relationship hydrators, where a hydrator is a key-value pair:
     * the key is the specific relationship name which comes from the request and the value is a
     * callable which hydrate the previous relationship.
     * These callables receive the domain object (which will be hydrated), an object representing the
     * currently processed relationship (it can be a ToOneRelationship or a ToManyRelationship
     * object), the "data" part of the request and the relationship name as their arguments, and
     * they should mutate the state of the domain object.
     * If it is an immutable object or an array (and passing by reference isn't used),
     * the callable should return the domain object.
     *
     * @param array $book
     * @return callable[]
     */
    protected function getRelationshipHydrator($book): array
    {
        return [
            "authors" => function (array $book, ToManyRelationship $authors, $data, $relationshipName) {
                if ($authors->isEmpty()) {
                    $book["authors"] = [];
                } else {
                    $book["authors"] = BookRepository::getAuthors($authors->getResourceIdentifierIds());
                }

                return $book;
            },
            "publisher" => function (array &$book, ToOneRelationship $publisher, $data, $relationshipName) {
                if ($publisher->isEmpty()) {
                    $book["publisher"] = null;
                } else {
                    $book["publisher"] = BookRepository::getPublisher($publisher->getResourceIdentifier()->getId());
                }
            }
        ];
    }
}
