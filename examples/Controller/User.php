<?php
namespace WoohooLabs\Yin\Examples\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\Examples\JsonApi\Document\UserDocument;
use WoohooLabs\Yin\Examples\JsonApi\Resource\ContactResourceTransformer;
use WoohooLabs\Yin\Examples\JsonApi\Resource\UserResourceTransformer;
use WoohooLabs\Yin\Examples\Repository\UserRepository;
use WoohooLabs\Yin\JsonApi\Request\Request;

class User
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        if (isset($_GET["id"])) {
            $id = $_GET["id"];
        } else {
            die("You must define the 'id' query parameter with a value of '1' or '2'!");
        }

        $resource = UserRepository::getUser($id);

        $document = new UserDocument(new UserResourceTransformer(new ContactResourceTransformer()));

        return $document->getResponse($response, $resource, new Request($request));
    }
}
