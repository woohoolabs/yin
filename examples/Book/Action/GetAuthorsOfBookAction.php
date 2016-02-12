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
        $bookId = $jsonApi->getRequest()->getQueryParam("book-id");
        if ($bookId === null) {
            die("You must define the 'book-id' query parameter with a value of '1'!");
        }

        // Retrieving a book domain object with an ID of $id
        $authors = BookRepository::getAuthorsOfBook($bookId);

        // Instantiating a book document
        $document = new AuthorsDocument(new AuthorResourceTransformer());

        // Responding with "200 Ok" status code along with the requested authors document
        return $jsonApi->respond()->ok($document, $authors);
   }
}
