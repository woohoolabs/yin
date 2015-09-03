<?php
namespace WoohooLabs\Yin\Examples\User\Action;

use WoohooLabs\Yin\Examples\User\JsonApi\Document\UsersDocument;
use WoohooLabs\Yin\Examples\User\JsonApi\Resource\ContactResourceTransformer;
use WoohooLabs\Yin\Examples\User\JsonApi\Resource\UserResourceTransformer;
use WoohooLabs\Yin\Examples\User\Repository\UserRepository;
use WoohooLabs\Yin\JsonApi\JsonApi;

class GetUsersAction
{
    /**
     * @param \WoohooLabs\Yin\JsonApi\JsonApi $jsonApi
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(JsonApi $jsonApi)
    {
        // Retrieving pagination information from the request, page = 1, size = 10 if it is missing
        $pagination = $jsonApi->getRequest()->getPageBasedPagination(1, 10);

        // Fetching users of the current page
        $users = UserRepository::getUsers($pagination->getPage(), $pagination->getSize());

        // Instantiating the users document
        $document = new UsersDocument(new UserResourceTransformer(new ContactResourceTransformer()));

        // Responding with "200 Ok" status code along with the users document
        return $jsonApi->fetchResponse()->ok($document, $users);
    }
}
