<?php
namespace WoohooLabs\Yin\Examples\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\Examples\JsonApi\Document\UsersDocument;
use WoohooLabs\Yin\Examples\JsonApi\Resource\ContactResourceTransformer;
use WoohooLabs\Yin\Examples\JsonApi\Resource\UserResourceTransformer;
use WoohooLabs\Yin\Examples\Repository\UserRepository;
use WoohooLabs\Yin\JsonApi\Request\Request;

class Users
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        $resource = UserRepository::getUsers();

        $document = new UsersDocument(new UserResourceTransformer(new ContactResourceTransformer()));

        return $document->getResponse($response, $resource, Request::fromRequest($request));
    }
}
