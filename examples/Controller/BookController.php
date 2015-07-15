<?php
namespace WoohooLabs\Yin\Examples\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\Examples\JsonApi\Document\BookDocument;
use WoohooLabs\Yin\Examples\JsonApi\Resource\AuthorResourceTransformer;
use WoohooLabs\Yin\Examples\JsonApi\Resource\BookResourceTransformer;
use WoohooLabs\Yin\Examples\JsonApi\Resource\PublisherResourceTransformer;
use WoohooLabs\Yin\JsonApi\Request\Criteria;

class BookController
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        $resource = [
            "id" => "12345",
            "title" => "Example Book",
            "authors" => [
                [
                    "id" => "11111",
                    "name" => "John Doe"
                ],
                [
                    "id" => "11112",
                    "name" => "Jane Doe"
                ]
            ],
            "publisher" => [
                "id" => "12346",
                "name" => "Example Publisher"
            ]
        ];

        $document = new BookDocument(
            $response,
            $resource,
            new BookResourceTransformer(new AuthorResourceTransformer(), new PublisherResourceTransformer())
        );

        return $document->getResponse(200, new Criteria($request));
    }
}
