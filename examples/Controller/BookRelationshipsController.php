<?php
namespace WoohooLabs\Yin\Examples\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\Examples\JsonApi\Document\BookDocument;
use WoohooLabs\Yin\Examples\JsonApi\Resource\AuthorResourceTransformer;
use WoohooLabs\Yin\Examples\JsonApi\Resource\BookResourceTransformer;
use WoohooLabs\Yin\Examples\JsonApi\Resource\PublisherResourceTransformer;
use WoohooLabs\Yin\JsonApi\Request\Request;

class BookRelationshipsController
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
            "pages" => "200",
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
            new BookResourceTransformer(new AuthorResourceTransformer(), new PublisherResourceTransformer())
        );

        if (isset($_GET["relationship"])) {
            $relationshipName = $_GET["relationship"];
        } else {
            die("You must define the 'relationship' query parameter with a value of 'authors' or 'publisher'!");
        }

        return $document->getRelationshipResponse($relationshipName, $response, $resource, new Request($request));
    }
}
