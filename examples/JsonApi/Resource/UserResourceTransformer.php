<?php
namespace WoohooLabs\Yin\Examples\JsonApi\Resource;

use WoohooLabs\Yin\JsonApi\Schema\Attributes;
use WoohooLabs\Yin\JsonApi\Schema\ToManyRelationship;
use WoohooLabs\Yin\JsonApi\Schema\Relationships;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractResourceTransformer;

class UserResourceTransformer extends AbstractResourceTransformer
{
    /**
     * @var ContactResourceTransformer
     */
    private $contactTransformer;

    /**
     * @param ContactResourceTransformer $contactTransformer
     */
    public function __construct(ContactResourceTransformer $contactTransformer)
    {
        $this->contactTransformer = $contactTransformer;
    }

    /**
     * @param mixed $resource
     * @return string
     */
    public function getType($resource)
    {
        return "user";
    }

    /**
     * @param mixed $resource
     * @return string
     */
    public function getId($resource)
    {
        return $resource["id"];
    }

    /**
     * @param mixed $resource
     * @return array
     */
    public function getMeta($resource)
    {
        return [];
    }

    /**
     * @param mixed $resource
     * @return \WoohooLabs\Yin\JsonApi\Schema\Links|null
     */
    public function getLinks($resource)
    {
        return null;
    }

    /**
     * @param mixed $resource
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
     * @param mixed $resource
     * @param string $baseRelationshipPath
     * @return \WoohooLabs\Yin\JsonApi\Schema\Relationships|null
     */
    public function getRelationships($resource, $baseRelationshipPath)
    {
        return new Relationships(
            [
                "contacts" => function($resource, $baseRelationshipPath) {
                    return ToManyRelationship::create()
                        ->setData($resource["contacts"], $this->contactTransformer);
                }
            ]
        );
    }
}
