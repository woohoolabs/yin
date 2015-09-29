<?php
namespace WoohooLabs\Yin\JsonApi\Transformer;

use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface;

interface ResourceTransformerInterface
{
    /**
     * Provides information about the "type" section of the current resource.
     *
     * The method returns the type of the current resource.
     *
     * @param mixed $domainObject
     * @return string
     */
    public function getType($domainObject);

    /**
     * Provides information about the "id" section of the current resource.
     *
     * The method returns the ID of the current resource which should be a UUID.
     *
     * @param mixed $domainObject
     * @return string
     */
    public function getId($domainObject);

    /**
     * Provides information about the "meta" section of the current resource.
     *
     * The method returns an array of non-standard meta information about the resource. If
     * this array is empty, the section won't appear in the response.
     *
     * @param mixed $domainObject
     * @return array
     */
    public function getMeta($domainObject);

    /**
     * Provides information about the "links" section of the current resource.
     *
     * The method returns a new Links schema object if you want to provide linkage
     * data about the resource or null if it should be omitted from the response.
     *
     * @param mixed $domainObject
     * @return \WoohooLabs\Yin\JsonApi\Schema\Links|null
     */
    public function getLinks($domainObject);

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
    public function getAttributes($domainObject);

    /**
     * Returns an array of relationship names which are included in the response by default.
     *
     * @param mixed $domainObject
     * @return array
     */
    public function getDefaultRelationships($domainObject);

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
    public function getRelationships($domainObject);

    /**
     * @param mixed $domainObject
     * @return array|null
     */
    public function transformToResourceIdentifier($domainObject);

    /**
     * @param mixed $domainObject
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface $data
     * @param string $baseRelationshipPath
     * @return array
     */
    public function transformToResource(
        $domainObject,
        RequestInterface $request,
        DataInterface $data,
        $baseRelationshipPath = ""
    );

    /**
     * @param mixed $domainObject
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface $data
     * @param string $relationshipName
     * @param string $baseRelationshipPath
     * @return array
     */
    public function transformRelationship(
        $domainObject,
        RequestInterface $request,
        DataInterface $data,
        $relationshipName,
        $baseRelationshipPath = ""
    );
}
