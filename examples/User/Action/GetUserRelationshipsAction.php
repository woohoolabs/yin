<?php
namespace WoohooLabs\Yin\Examples\User\Action;

use WoohooLabs\Yin\Examples\User\JsonApi\Document\UserDocument;
use WoohooLabs\Yin\Examples\User\JsonApi\Resource\ContactResourceTransformer;
use WoohooLabs\Yin\Examples\User\JsonApi\Resource\UserResourceTransformer;
use WoohooLabs\Yin\Examples\User\Repository\UserRepository;
use WoohooLabs\Yin\JsonApi\JsonApi;

class GetUserRelationshipsAction
{
    /**
     * @param \WoohooLabs\Yin\JsonApi\JsonApi $jsonApi
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(JsonApi $jsonApi)
    {
        // Checking the "id" of the currently requested user
        $id = $jsonApi->getRequest()->getQueryParam("id");
        if ($id === null) {
            die("You must define the 'id' query parameter with a value of '1' or '2'!");
        }

        // Checking the currently requested relationship's name
        $relationshipName = $jsonApi->getRequest()->getQueryParam("rel");
        if ($relationshipName === null) {
            die("You must define the 'rel' query parameter with a value of 'contacts'!");
        }

        // Retrieving a user domain object with an ID of $id
        $user = UserRepository::getUser($id);

        // Instantiating a book document
        $document = new UserDocument(new UserResourceTransformer(new ContactResourceTransformer()));

        // Responding with "200 Ok" status code along with the requested relationship document
        return $jsonApi->respondWithRelationship($relationshipName)->ok($document, $user);
    }
}
