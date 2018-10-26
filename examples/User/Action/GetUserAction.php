<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Examples\User\Action;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\Examples\User\JsonApi\Document\UserDocument;
use WoohooLabs\Yin\Examples\User\JsonApi\Resource\ContactResourceTransformer;
use WoohooLabs\Yin\Examples\User\JsonApi\Resource\UserResourceTransformer;
use WoohooLabs\Yin\Examples\User\Repository\UserRepository;
use WoohooLabs\Yin\JsonApi\JsonApi;

class GetUserAction
{
    public function __invoke(JsonApi $jsonApi): ResponseInterface
    {
        // Checking the "id" of the currently requested user
        $id = (int) $jsonApi->getRequest()->getAttribute("id");

        // Retrieving a user domain object with an ID of $id
        $user = UserRepository::getUser($id);

        // Instantiating a user document
        $document = new UserDocument(new UserResourceTransformer(new ContactResourceTransformer()));

        // Responding with "200 Ok" status code along with the user document
        return $jsonApi->respond()->ok($document, $user);
    }
}
