<?php
namespace WoohooLabs\Yin\Examples\User\JsonApi\Resource;

use WoohooLabs\Yin\JsonApi\Schema\Relationship\ToManyRelationship;
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
     * Provides information about the "type" section of the current resource.
     *
     * The method returns the type of the current resource.
     *
     * @param array $user
     * @return string
     */
    public function getType($user)
    {
        return "user";
    }

    /**
     * Provides information about the "id" section of the current resource.
     *
     * The method returns the ID of the current resource which should be a UUID.
     *
     * @param array $user
     * @return string
     */
    public function getId($user)
    {
        return $user["id"];
    }

    /**
     * Provides information about the "meta" section of the current resource.
     *
     * The method returns an array of non-standard meta information about the resource. If
     * this array is empty, the section won't appear in the response.
     *
     * @param array $user
     * @return array
     */
    public function getMeta($user)
    {
        return [];
    }

    /**
     * Provides information about the "links" section of the current resource.
     *
     * The method returns a new Links schema object if you want to provide linkage
     * data about the resource or null if it should be omitted from the response.
     *
     * @param array $user
     * @return \WoohooLabs\Yin\JsonApi\Schema\Links|null
     */
    public function getLinks($user)
    {
        return null;
    }

    /**
     * Provides information about the "attributes" section of the current resource.
     *
     * The method returns an array where the keys signify the attribute names,
     * while the values are closures receiving the domain object as an argument,
     * and they should return the value of the corresponding attribute.
     *
     * @param array $user
     * @return array
     */
    public function getAttributes($user)
    {
        return [
            "firstname" => function(array $user) { return $user["firstname"]; },
            "surname" => function(array $user) { return $user["lastname"]; },
        ];
    }

    /**
     * Returns an array of relationship names which are included in the response by default.
     *
     * @param array $user
     * @return array
     */
    public function getDefaultIncludedRelationships($user)
    {
        return [];
    }

    /**
     * Provides information about the "relationships" section of the current resource.
     *
     * The method returns an array where the keys signify the relationship names,
     * while the values are closures receiving the domain object as an argument,
     * and they should return a new relationship instance (to-one or to-many).
     *
     * @param array $user
     * @return array
     */
    public function getRelationships($user)
    {
        return [
            "contacts" => function(array $user) {
                return
                    ToManyRelationship::create()
                        ->setData($user["contacts"], $this->contactTransformer)
                    ;
            }
        ];
    }
}
