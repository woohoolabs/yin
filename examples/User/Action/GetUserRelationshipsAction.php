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
        $id = $jsonApi->getRequest()->getQueryParam("id");
        if ($id === null) {
            die("You must define the 'id' query parameter with a value of '1' or '2'!");
        }

        $relationshipName = $jsonApi->getRequest()->getQueryParam("relationship");
        if ($relationshipName === null) {
            die("You must define the 'relationship' query parameter with a value of 'contacts'!");
        }

        $resource = UserRepository::getUser($id);

        $document = new UserDocument(new UserResourceTransformer(new ContactResourceTransformer()));

        return $jsonApi->fetchRelationshipResponse($relationshipName)->ok($document, $resource);
    }
}
