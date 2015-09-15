<?php
namespace WoohooLabsTest\Yin\JsonApi\Utils;

use WoohooLabs\Yin\JsonApi\Schema\Links;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractResourceTransformer;

class StubResourceTransformer extends AbstractResourceTransformer
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var array
     */
    protected $meta;

    /**
     * @var \WoohooLabs\Yin\JsonApi\Schema\Links
     */
    protected $links;

    /**
     * @var array
     */
    protected $attributes;

    /**
     * @var array
     */
    protected $defaultRelationships;

    /**
     * @var \WoohooLabs\Yin\JsonApi\Schema\Included
     */
    protected $relationships;

    /**
     * @param string $type
     * @param string $id
     * @param array $meta
     * @param \WoohooLabs\Yin\JsonApi\Schema\Links|null $links
     * @param array $attributes
     * @param array $defaultRelationships
     * @param array $relationships
     */
    public function __construct(
        $type = "",
        $id = "",
        array $meta = [],
        Links $links = null,
        array $attributes = [],
        array $defaultRelationships = [],
        array $relationships = []
    ) {
        $this->type = $type;
        $this->id = $id;
        $this->meta = $meta;
        $this->links = $links;
        $this->attributes = $attributes;
        $this->defaultRelationships = $defaultRelationships;
        $this->relationships = $relationships;
    }

    /**
     * Provides information about the "type" section of the current resource.
     *
     * The method returns the type of the current resource.
     *
     * @param mixed $domainObject
     * @return string
     */
    public function getType($domainObject)
    {
        return $this->type;
    }

    /**
     * Provides information about the "id" section of the current resource.
     *
     * The method returns the ID of the current resource which should be a UUID.
     *
     * @param mixed $domainObject
     * @return string
     */
    public function getId($domainObject)
    {
        return $this->id;
    }

    /**
     * Provides information about the "meta" section of the current resource.
     *
     * The method returns an array of non-standard meta information about the resource. If
     * this array is empty, the section won't appear in the response.
     *
     * @param mixed $domainObject
     * @return array
     */
    public function getMeta($domainObject)
    {
        return $this->meta;
    }

    /**
     * Provides information about the "links" section of the current resource.
     *
     * The method returns a new Links schema object if you want to provide linkage
     * data about the resource or null if it should be omitted from the response.
     *
     * @param mixed $domainObject
     * @return \WoohooLabs\Yin\JsonApi\Schema\Links|null
     */
    public function getLinks($domainObject)
    {
        return $this->links;
    }

    /**
     * Provides information about the "attributes" section of the current resource.
     *
     * The method returns an array where the keys signify the attribute names,
     * while the values are closures receiving the domain object as an argument,
     * and they should return the value of the corresponding attribute.
     *
     * @param mixed $domainObject
     * @return array
     */
    public function getAttributes($domainObject)
    {
        return $this->attributes;
    }

    /**
     * Returns an array of relationship names which are included in the response by default.
     *
     * @param mixed $domainObject
     * @return array
     */
    public function getDefaultRelationships($domainObject)
    {
        return $this->defaultRelationships;
    }

    /**
     * Provides information about the "relationships" section of the current resource.
     *
     * The method returns an array where the keys signify the relationship names,
     * while the values are closures receiving the domain object as an argument,
     * and they should return a new relationship instance (to-one or to-many).
     *
     * @param mixed $domainObject
     * @return array
     */
    public function getRelationships($domainObject)
    {
        return $this->relationships;
    }
}
