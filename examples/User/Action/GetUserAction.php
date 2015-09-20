<?php
namespace WoohooLabs\Yin\Examples\User\Action;

use WoohooLabs\Yin\Examples\User\JsonApi\Document\UserDocument;
use WoohooLabs\Yin\Examples\User\JsonApi\Resource\ContactResourceTransformer;
use WoohooLabs\Yin\Examples\User\JsonApi\Resource\UserResourceTransformer;
use WoohooLabs\Yin\Examples\User\Repository\UserRepository;
use WoohooLabs\Yin\JsonApi\JsonApi;

class GetUserAction
{
    /**
     * @param \WoohooLabs\Yin\JsonApi\JsonApi $jsonApi
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(JsonApi $jsonApi)
    {
        // Checking if the "id" query parameter is set
        $id = $jsonApi->getRequest()->getQueryParam("id");
        if ($id === null) {
            die("You must define the 'id' query parameter with a value of '1' or '2'!");
        }

        // Retrieving a user domain model with an ID of $id
        $user = UserRepository::getUser($id);

        // Instantiating the user document
        $document = new UserDocument(new UserResourceTransformer(new ContactResourceTransformer()));

        // Responding with "200 Ok" status code along with the user document
        return $jsonApi->fetchResponse()->ok($document, $user);
    }
}
