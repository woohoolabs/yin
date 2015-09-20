<?php
namespace WoohooLabs\Yin\Examples\Book\Action;

use WoohooLabs\Yin\Examples\Book\JsonApi\Document\BookDocument;
use WoohooLabs\Yin\Examples\Book\JsonApi\Resource\AuthorResourceTransformer;
use WoohooLabs\Yin\Examples\Book\JsonApi\Hydrator\BookHydator;
use WoohooLabs\Yin\Examples\Book\JsonApi\Resource\BookResourceTransformer;
use WoohooLabs\Yin\Examples\Book\JsonApi\Resource\PublisherResourceTransformer;
use WoohooLabs\Yin\Examples\Book\Repository\BookRepository;
use WoohooLabs\Yin\JsonApi\JsonApi;

class UpdateBookAction
{
    /**
     * @param \WoohooLabs\Yin\JsonApi\JsonApi $jsonApi
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(JsonApi $jsonApi)
    {
        // Retrieving a book domain model with an ID of $id
        $id = $jsonApi->getRequest()->getBodyDataId();
        $book = BookRepository::getBook($id);
        if ($book === null) {
            die("A book with an ID of '$id' can't be found!");
        }

        // Hydrating the retrieved book domain model from the request
        $hydrator = new BookHydator();
        $book = $hydrator->hydrate($jsonApi->getRequest(), $book);

        // Instantiating a book document
        $document = new BookDocument(
            new BookResourceTransformer(new AuthorResourceTransformer(), new PublisherResourceTransformer())
        );

        // Responding with "200 Ok" status code along with the book document
        return $jsonApi->updateResponse()->ok($document, $book);
    }
}
