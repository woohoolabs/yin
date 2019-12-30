<?php

declare(strict_types=1);

namespace WoohooLabs\Yin\Examples\Book\Action;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\Examples\Book\JsonApi\Document\BooksDocument;
use WoohooLabs\Yin\Examples\Book\JsonApi\Resource\AuthorResource;
use WoohooLabs\Yin\Examples\Book\JsonApi\Resource\BookResource;
use WoohooLabs\Yin\Examples\Book\JsonApi\Resource\PublisherResource;
use WoohooLabs\Yin\Examples\Book\JsonApi\Resource\RepresentativeResource;
use WoohooLabs\Yin\Examples\Book\Repository\BookRepository;
use WoohooLabs\Yin\JsonApi\JsonApi;

class GetBooksAction
{
    public function __invoke(JsonApi $jsonApi): ResponseInterface
    {
        // Extracting pagination information from the request, page = 1, size = 10 if it is missing
        $pagination = $jsonApi->getPaginationFactory()->createPageBasedPagination(1, 10);

        // Retrieving a paginated collection of Book domain objects
        $books = BookRepository::getBooks($pagination->getPage(), $pagination->getSize());

        // Instantiating a Books document
        $document = new BooksDocument(
            new BookResource(
                new AuthorResource(),
                new PublisherResource(
                    new RepresentativeResource()
                )
            )
        );

        // Responding with "200 Ok" status code along with the Books document
        return $jsonApi->respond()->ok($document, $books);
    }
}
