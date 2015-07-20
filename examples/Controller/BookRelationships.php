<?php
namespace WoohooLabs\Yin\Examples\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\Examples\JsonApi\Document\BookDocument;
use WoohooLabs\Yin\Examples\JsonApi\Resource\AuthorResourceTransformer;
use WoohooLabs\Yin\Examples\JsonApi\Resource\BookResourceTransformer;
use WoohooLabs\Yin\Examples\JsonApi\Resource\PublisherResourceTransformer;
use WoohooLabs\Yin\Examples\Repository\BookRepository;
use WoohooLabs\Yin\JsonApi\Request\Request;

class BookRelationships
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        if (isset($_GET["relationship"])) {
            $relationshipName = $_GET["relationship"];
        } else {
            die("You must define the 'relationship' query parameter with a value of 'authors' or 'publisher'!");
        }

        $resource = BookRepository::getBook(1);

        $document = new BookDocument(
            new BookResourceTransformer(new AuthorResourceTransformer(), new PublisherResourceTransformer())
        );

        return $document->getRelationshipResponse($relationshipName, $response, $resource, new Request($request));
    }
}
