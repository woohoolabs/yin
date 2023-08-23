<?php

declare(strict_types=1);

namespace Devleand\Yin\Examples\Book\Action;

use Psr\Http\Message\ResponseInterface;
use Devleand\Yin\Examples\Book\JsonApi\Document\BooksDocument;
use Devleand\Yin\Examples\Book\JsonApi\Resource\AuthorResource;
use Devleand\Yin\Examples\Book\JsonApi\Resource\BookResource;
use Devleand\Yin\Examples\Book\JsonApi\Resource\PublisherResource;
use Devleand\Yin\Examples\Book\JsonApi\Resource\RepresentativeResource;
use Devleand\Yin\Examples\Book\Repository\BookRepository;
use Devleand\Yin\JsonApi\JsonApi;

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
