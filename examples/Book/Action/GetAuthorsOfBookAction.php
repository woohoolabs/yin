<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Examples\Book\Action;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\Examples\Book\JsonApi\Document\AuthorsDocument;
use WoohooLabs\Yin\Examples\Book\JsonApi\Resource\AuthorResourceTransformer;
use WoohooLabs\Yin\Examples\Book\Repository\BookRepository;
use WoohooLabs\Yin\JsonApi\JsonApi;

class GetAuthorsOfBookAction
{
    public function __invoke(JsonApi $jsonApi): ResponseInterface
    {
        // Checking the "id" of the currently requested book
        $bookId = (int) $jsonApi->getRequest()->getAttribute("id");

        // Retrieving the author domain objects for the book with an ID of $bookId
        $authors = BookRepository::getAuthorsOfBook($bookId);

        // Instantiating an authors document
        $document = new AuthorsDocument(new AuthorResourceTransformer(), $bookId);

        // Responding with "200 Ok" status code along with the requested authors document
        return $jsonApi->respond()->ok($document, $authors);
    }
}
