<?php

declare(strict_types=1);

namespace WoohooLabs\Yin\Examples\User\Action;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\Examples\User\JsonApi\Document\UsersDocument;
use WoohooLabs\Yin\Examples\User\JsonApi\Resource\ContactResource;
use WoohooLabs\Yin\Examples\User\JsonApi\Resource\UserResource;
use WoohooLabs\Yin\Examples\User\Repository\UserRepository;
use WoohooLabs\Yin\JsonApi\JsonApi;

class GetUsersAction
{
    public function __invoke(JsonApi $jsonApi): ResponseInterface
    {
        // Extracting pagination information from the request, page = 1, size = 10 if it is missing
        $pagination = $jsonApi->getPaginationFactory()->createPageBasedPagination(1, 10);

        // Retrieving a paginated collection of user domain objects
        $users = UserRepository::getUsers($pagination->getPage(), $pagination->getSize());

        // Instantiating a users document
        $document = new UsersDocument(new UserResource(new ContactResource()));

        // Responding with "200 Ok" status code along with the users document
        return $jsonApi->respond()->ok($document, $users);
    }
}
