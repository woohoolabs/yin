<?php
namespace Src\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Src\JsonApi\Document\BookDocument;
use Src\JsonApi\Resource\AuthorResourceTransformer;
use Src\JsonApi\Resource\BookResourceTransformer;
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
            "print" => "2015-01-01 01:11:00",
            "authors" => [
                [
                    "id" => "11111",
                    "name" => "John Doe"
                ],
                [
                    "id" => "11112",
                    "name" => "Jane Doe"
                ]
            ]
        ];

        $document = new BookDocument($response, $resource, new BookResourceTransformer(new AuthorResourceTransformer()));

        return $document->getResponse(200, new Criteria($request));
    }
}
