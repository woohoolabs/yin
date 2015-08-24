<?php
namespace WoohooLabs\Yin\Examples\User\JsonApi\Resource;

use WoohooLabs\Yin\JsonApi\Schema\Attributes;
use WoohooLabs\Yin\JsonApi\Schema\ToManyRelationship;
use WoohooLabs\Yin\JsonApi\Schema\Relationships;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractResourceTransformer;

class UserResourceTransformer extends AbstractResourceTransformer
{
    /**
     * @var \WoohooLabs\Yin\Examples\User\JsonApi\Resource\ContactResourceTransformer
     */
    private $contactTransformer;

    /**
     * @param \WoohooLabs\Yin\Examples\User\JsonApi\Resource\ContactResourceTransformer $contactTransformer
     */
    public function __construct(ContactResourceTransformer $contactTransformer)
    {
        $this->contactTransformer = $contactTransformer;
    }

    /**
     * @param array $user
     * @return string
     */
    public function getType($user)
    {
        return "user";
    }

    /**
     * @param array $user
     * @return string
     */
    public function getId($user)
    {
        return $user["id"];
    }

    /**
     * @param array $user
     * @return array
     */
    public function getMeta($user)
    {
        return [];
    }

    /**
     * @param array $user
     * @return \WoohooLabs\Yin\JsonApi\Schema\Links|null
     */
    public function getLinks($user)
    {
        return null;
    }

    /**
     * @param array $user
     * @return \WoohooLabs\Yin\JsonApi\Schema\Attributes|null
     */
    public function getAttributes($user)
    {
        return new Attributes(
            [
                "firstname" => function(array $user) { return $user["firstname"]; },
                "surname" => function(array $user) { return $user["lastname"]; },
            ]
        );
    }

    /**
     * @param array $user
     * @return \WoohooLabs\Yin\JsonApi\Schema\Relationships|null
     */
    public function getRelationships($user)
    {
        return new Relationships(
            [
                "contacts" => function(array $user) {
                    return ToManyRelationship::create()
                        ->setData($user["contacts"], $this->contactTransformer);
                }
            ]
        );
    }
}
