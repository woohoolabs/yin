<?php

declare(strict_types=1);

namespace Devleand\Yin\Examples\Book\Action;

use Psr\Http\Message\ResponseInterface;
use Devleand\Yin\Examples\Book\JsonApi\Document\BookDocument;
use Devleand\Yin\Examples\Book\JsonApi\Hydrator\BookHydator;
use Devleand\Yin\Examples\Book\JsonApi\Resource\AuthorResource;
use Devleand\Yin\Examples\Book\JsonApi\Resource\BookResource;
use Devleand\Yin\Examples\Book\JsonApi\Resource\PublisherResource;
use Devleand\Yin\Examples\Book\JsonApi\Resource\RepresentativeResource;
use Devleand\Yin\JsonApi\JsonApi;

class CreateBookAction
{
    public function __invoke(JsonApi $jsonApi): ResponseInterface
    {
        // Hydrating a new book domain object from the request
        $book = $jsonApi->hydrate(new BookHydator(), []);

        // Instantiating a book document
        $document = new BookDocument(
            new BookResource(
                new AuthorResource(),
                new PublisherResource(
                    new RepresentativeResource()
                )
            )
        );

        // Responding with "201 Created" status code along with the book document
        return $jsonApi->respond()->created($document, $book);
    }
}
