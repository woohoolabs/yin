<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Examples\User\JsonApi\Resource;

use WoohooLabs\Yin\JsonApi\Schema\Link\Link;
use WoohooLabs\Yin\JsonApi\Schema\Link\RelationshipLinks;
use WoohooLabs\Yin\JsonApi\Schema\Link\ResourceLinks;
use WoohooLabs\Yin\JsonApi\Schema\Relationship\ToManyRelationship;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractResourceTransformer;

class UserResourceTransformer extends AbstractResourceTransformer
{
    /**
     * @var ContactResourceTransformer
     */
    private $contactTransformer;

    public function __construct(ContactResourceTransformer $contactTransformer)
    {
        $this->contactTransformer = $contactTransformer;
    }

    /**
     * Provides information about the "type" member of the current resource.
     *
     * The method returns the type of the current resource.
     *
     * @param array $user
     */
    public function getType($user): string
    {
        return "users";
    }

    /**
     * Provides information about the "id" member of the current resource.
     *
     * The method returns the ID of the current resource which should be a UUID.
     *
     * @param array $user
     */
    public function getId($user): string
    {
        return (string) $user["id"];
    }

    /**
     * Provides information about the "meta" member of the current resource.
     *
     * The method returns an array of non-standard meta information about the resource. If
     * this array is empty, the member won't appear in the response.
     *
     * @param array $user
     */
    public function getMeta($user): array
    {
        return [];
    }

    /**
     * Provides information about the "links" member of the current resource.
     *
     * The method returns a new ResourceLinks object if you want to provide linkage
     * data about the resource or null if it should be omitted from the response.
     *
     * @param array $user
     */
    public function getLinks($user): ?ResourceLinks
    {
        return null;
    }

    /**
     * Provides information about the "attributes" member of the current resource.
     *
     * The method returns an array where the keys signify the attribute names,
     * while the values are callables receiving the domain object as an argument,
     * and they should return the value of the corresponding attribute.
     *
     * @param array $user
     * @return callable[]
     */
    public function getAttributes($user): array
    {
        return [
            "firstname" => function (array $user) {
                return $user["firstname"];
            },
            "surname" => function (array $user) {
                return $user["lastname"];
            },
        ];
    }

    /**
     * Returns an array of relationship names which are included in the response by default.
     *
     * @param array $user
     */
    public function getDefaultIncludedRelationships($user): array
    {
        return [];
    }

    /**
     * Provides information about the "relationships" member of the current resource.
     *
     * The method returns an array where the keys signify the relationship names,
     * while the values are callables receiving the domain object as an argument,
     * and they should return a new relationship instance (to-one or to-many).
     *
     * @param array $user
     * @return callable[]
     */
    public function getRelationships($user): array
    {
        return [
            "contacts" => function (array $user) {
                return
                    ToManyRelationship::create()
                        ->setLinks(
                            RelationshipLinks::createWithoutBaseUri(
                                new Link("/?path=/users/" . $user["id"] . "/contacts"),
                                new Link("/?path=/users/" . $user["id"] . "/relationships/contacts")
                            )
                        )
                        ->setDataAsCallable(function () use ($user) {
                            return $user["contacts"];
                        }, $this->contactTransformer)
                        ->omitWhenNotIncluded()
                    ;
            }
        ];
    }
}
