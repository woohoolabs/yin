<?php
namespace WoohooLabs\Yin\Examples\Book\Action;

use WoohooLabs\Yin\Examples\Book\JsonApi\Document\BookDocument;
use WoohooLabs\Yin\Examples\Book\JsonApi\Resource\AuthorResourceTransformer;
use WoohooLabs\Yin\Examples\Book\JsonApi\Hydrator\BookHydator;
use WoohooLabs\Yin\Examples\Book\JsonApi\Resource\BookResourceTransformer;
use WoohooLabs\Yin\Examples\Book\JsonApi\Resource\PublisherResourceTransformer;
use WoohooLabs\Yin\JsonApi\JsonApi;

class CreateBookAction
{
    /**
     * @param \WoohooLabs\Yin\JsonApi\JsonApi $jsonApi
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(JsonApi $jsonApi)
    {
        // Hydrating a new book domain object from the request
        $book = $jsonApi->hydrate(new BookHydator(), []);

        // Instantiating a book document
        $document = new BookDocument(
            new BookResourceTransformer(new AuthorResourceTransformer(), new PublisherResourceTransformer())
        );

        // Responding with "201 Created" status code along with the book document
        return $jsonApi->respond()->created($document, $book);
    }
}
