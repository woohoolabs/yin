<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Examples\Book\Action;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\Examples\Book\JsonApi\Document\BookDocument;
use WoohooLabs\Yin\Examples\Book\JsonApi\Resource\AuthorResourceTransformer;
use WoohooLabs\Yin\Examples\Book\JsonApi\Resource\BookResourceTransformer;
use WoohooLabs\Yin\Examples\Book\JsonApi\Resource\PublisherResourceTransformer;
use WoohooLabs\Yin\Examples\Book\JsonApi\Resource\RepresentativeResourceTransformer;
use WoohooLabs\Yin\Examples\Book\Repository\BookRepository;
use WoohooLabs\Yin\JsonApi\JsonApi;

class GetBookAction
{
    public function __invoke(JsonApi $jsonApi): ResponseInterface
    {
        // Checking the "id" of the currently requested book
        $id = (int) $jsonApi->getRequest()->getAttribute("id");

        // Retrieving a book domain object with an ID of $id
        $book = BookRepository::getBook($id);

        // Instantiating a book document
        $document = new BookDocument(
            new BookResourceTransformer(
                new AuthorResourceTransformer(),
                new PublisherResourceTransformer(
                    new RepresentativeResourceTransformer()
                )
            )
        );

        // Responding with "200 Ok" status code along with the book document
        return $jsonApi->respond()->ok($document, $book);
    }
}
