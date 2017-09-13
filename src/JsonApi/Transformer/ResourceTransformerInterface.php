<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Transformer;

use WoohooLabs\Yin\JsonApi\Schema\Links;

interface ResourceTransformerInterface
{
    /**
     * Provides information about the "type" member of the current resource.
     *
     * The method returns the type of the current resource.
     *
     * @param mixed $domainObject
     */
    public function getType($domainObject): string;

    /**
     * Provides information about the "id" member of the current resource.
     *
     * The method returns the ID of the current resource which should be a UUID.
     *
     * @param mixed $domainObject
     */
    public function getId($domainObject): string;

    /**
     * Provides information about the "meta" member of the current resource.
     *
     * The method returns an array of non-standard meta information about the resource. If
     * this array is empty, the member won't appear in the response.
     *
     * @param mixed $domainObject
     */
    public function getMeta($domainObject): array;

    /**
     * Provides information about the "links" member of the current resource.
     *
     * The method returns a new Links schema object if you want to provide linkage
     * data about the resource or null if it should be omitted from the response.
     *
     * @param mixed $domainObject
     */
    public function getLinks($domainObject): ?Links;

    /**
     * Provides information about the "attributes" member of the current resource.
     *
     * The method returns an array where the keys signify the attribute names,
     * while the values are callables receiving the domain object as an argument,
     * and they should return the value of the corresponding attribute.
     *
     * @param mixed $domainObject
     * @return callable[]
     */
    public function getAttributes($domainObject): array;

    /**
     * Returns an array of relationship names which are included in the response by default.
     *
     * @param mixed $domainObject
     */
    public function getDefaultIncludedRelationships($domainObject): array;

    /**
     * Provides information about the "relationships" member of the current resource.
     *
     * The method returns an array where the keys signify the relationship names,
     * while the values are callables receiving the domain object as an argument,
     * and they should return a new relationship instance (to-one or to-many).
     *
     * @param mixed $domainObject
     * @return callable[]
     */
    public function getRelationships($domainObject): array;

    /**
     * @param mixed $domainObject
     */
    public function transformToResourceIdentifier($domainObject): ?array;

    /**
     * @param mixed $domainObject
     */
    public function transformToResource(Transformation $transformation, $domainObject): ?array;

    /**
     * @param mixed $domainObject
     */
    public function transformRelationship(
        string $relationshipName,
        Transformation $transformation,
        $domainObject,
        array $additionalMeta = []
    ): ?array;
}
