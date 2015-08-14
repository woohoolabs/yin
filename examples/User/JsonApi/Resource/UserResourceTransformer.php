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
     * @param array $resource
     * @return string
     */
    public function getType($resource)
    {
        return "user";
    }

    /**
     * @param array $resource
     * @return string
     */
    public function getId($resource)
    {
        return $resource["id"];
    }

    /**
     * @param array $resource
     * @return array
     */
    public function getMeta($resource)
    {
        return [];
    }

    /**
     * @param array $resource
     * @return \WoohooLabs\Yin\JsonApi\Schema\Links|null
     */
    public function getLinks($resource)
    {
        return null;
    }

    /**
     * @param array $resource
     * @return \WoohooLabs\Yin\JsonApi\Schema\Attributes|null
     */
    public function getAttributes($resource)
    {
        return new Attributes(
            [
                "firstname" => function($resource) { return $resource["firstname"]; },
                "surname" => function($resource) { return $resource["lastname"]; },
            ]
        );
    }

    /**
     * @param array $resource
     * @return \WoohooLabs\Yin\JsonApi\Schema\Relationships|null
     */
    public function getRelationships($resource)
    {
        return new Relationships(
            [
                "contacts" => function($resource) {
                    return ToManyRelationship::create()
                        ->setData($resource["contacts"], $this->contactTransformer);
                }
            ]
        );
    }
}
