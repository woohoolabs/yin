<?php
namespace WoohooLabs\Yin\Examples\JsonApi\Resource;

use WoohooLabs\Yin\JsonApi\Schema\Attributes;
use WoohooLabs\Yin\JsonApi\Schema\OneToManyTraversableRelationship;
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
    protected function getMeta($resource)
    {
        return [];
    }

    /**
     * @param mixed $resource
     * @param string $relationshipPath
     * @return \WoohooLabs\Yin\JsonApi\Schema\Links|null
     */
    protected function getLinks($resource, $relationshipPath)
    {
        return null;
    }

    /**
     * @param mixed $resource
     * @return \WoohooLabs\Yin\JsonApi\Schema\Attributes
     */
    protected function getAttributes($resource)
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
     * @return \WoohooLabs\Yin\JsonApi\Schema\Relationships
     */
    protected function getRelationships($resource)
    {
        return new Relationships([
            "contacts" => function($resource) {
                return new OneToManyTraversableRelationship($resource["contacts"], $this->contactTransformer);
            }
        ]);
    }
}
