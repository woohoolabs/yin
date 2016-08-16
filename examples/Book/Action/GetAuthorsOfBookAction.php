<?php
namespace WoohooLabs\Yin\Examples\Book\Action;

use WoohooLabs\Yin\Examples\Book\JsonApi\Document\AuthorsDocument;
use WoohooLabs\Yin\Examples\Book\JsonApi\Resource\AuthorResourceTransformer;
use WoohooLabs\Yin\Examples\Book\Repository\BookRepository;
use WoohooLabs\Yin\JsonApi\JsonApi;

class GetAuthorsOfBookAction
{
    /**
     * @param \WoohooLabs\Yin\JsonApi\JsonApi $jsonApi
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(JsonApi $jsonApi)
    {
        // Checking the "id" of the currently requested book
        $bookId = $jsonApi->getRequest()->getAttribute("id");

        // Retrieving the author domain objects for the book with an ID of $bookId
        $authors = BookRepository::getAuthorsOfBook($bookId);

        // Instantiating an authors document
        $document = new AuthorsDocument(new AuthorResourceTransformer(), $bookId);

        // Responding with "200 Ok" status code along with the requested authors document
        return $jsonApi->respond()->ok($document, $authors);
    }
}
