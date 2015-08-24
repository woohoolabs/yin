<?php
namespace WoohooLabs\Yin\JsonApi\Transformer;

use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Schema\Included;
use WoohooLabs\Yin\TransformerTrait;

abstract class AbstractResourceTransformer implements ResourceTransformerInterface
{
    use TransformerTrait;

    /**
     * Provides information about the "type" section of the current resource.
     *
     * The method returns the type of the current resource.
     *
     * @param mixed $domainObject
     * @return string
     */
    abstract public function getType($domainObject);

    /**
     * Provides information about the "meta" section of the current resource.
     *
     * The method returns the ID of the current resource which should be a UUID.
     *
     * @param mixed $domainObject
     * @return string
     */
    abstract public function getId($domainObject);

    /**
     * Provides information about the "meta" section of the current resource.
     *
     * The method returns an array of non-standard meta information about the resource. If
     * this array is empty, the section won't appear in the response.
     *
     * @param mixed $domainObject
     * @return array
     */
    abstract public function getMeta($domainObject);

    /**
     * Provides information about the "links" section of the current resource.
     *
     * The method returns a new Links schema object if you want to provide linkage
     * data about the resource or null if it should be omitted from the response.
     *
     * @param mixed $domainObject
     * @return \WoohooLabs\Yin\JsonApi\Schema\Links|null
     */
    abstract public function getLinks($domainObject);

    /**
     * Provides information about the "attributes" section of the current resource.
     *
     * The method returns a new Attributes schema object if you want the section to
     * appear in the response of null if it should be omitted.
     *
     * @param mixed $domainObject
     * @return \WoohooLabs\Yin\JsonApi\Schema\Attributes|null
     */
    abstract public function getAttributes($domainObject);

    /**
     * Provides information about the "relationships" section of the current resource.
     *
     * The method returns a new Relationships schema object if you want the section to
     * appear in the response of null if it should be omitted.
     *
     * @param mixed $domainObject
     * @return \WoohooLabs\Yin\JsonApi\Schema\Relationships|null
     */
    abstract public function getRelationships($domainObject);

    /**
     * Transforms the original resource to a JSON API resource identifier.
     *
     * @param mixed $domainObject
     * @return array|null
     */
    public function transformToResourceIdentifier($domainObject)
    {
        if ($domainObject === null) {
            return null;
        }

        $result = [
            "type" => $this->getType($domainObject),
            "id" => $this->getId($domainObject),
        ];

        // META
        $meta = $this->getMeta($domainObject);
        if (empty($meta) === false) {
            $result["meta"] = $meta;
        }

        return $result;
    }

    /**
     * Transforms the original resource to a JSON API resource.
     *
     * @param mixed $domainObject
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \WoohooLabs\Yin\JsonApi\Schema\Included $included
     * @param string $baseRelationshipPath
     * @return array|null
     */
    public function transformToResource(
        $domainObject,
        RequestInterface $request,
        Included $included,
        $baseRelationshipPath = ""
    ) {
        $result = $this->transformToResourceIdentifier($domainObject);

        if ($result === null) {
            return null;
        }

        // Links
        $this->transformLinksObject($result, $domainObject);

        // Attributes
        $this->transformAttributesObject($result, $domainObject, $request);

        // Relationships
        $this->transformRelationshipsObject($result, $domainObject, $request, $included, $baseRelationshipPath);

        return $result;
    }

    /**
     * Transforms a relationship with a name of $relationshipName of the original resource to a JSON API relationship.
     *
     * @param mixed $domainObject
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \WoohooLabs\Yin\JsonApi\Schema\Included $included
     * @param string $relationshipName
     * @param string $baseRelationshipPath
     * @return array|null
     */
    public function transformRelationship(
        $domainObject,
        RequestInterface $request,
        Included $included,
        $relationshipName,
        $baseRelationshipPath = ""
    ) {
        $relationships = $this->getRelationships($domainObject);

        if ($relationships === null) {
            return null;
        }

        return $relationships->transformRelationship(
            $relationshipName,
            $domainObject,
            $request,
            $included,
            $this->getType($domainObject),
            $baseRelationshipPath
        );
    }

    /**
     * @param array $array
     * @param mixed $domainObject
     */
    private function transformLinksObject(array &$array, $domainObject)
    {
        $links = $this->getLinks($domainObject);

        if (empty($links) === false) {
            $array["links"] = $links->transform();
        }
    }

    /**
     * @param array $array
     * @param $domainObject
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     */
    private function transformAttributesObject(array &$array, $domainObject, RequestInterface $request)
    {
        $attributes = $this->getAttributes($domainObject);
        if ($attributes !== null) {
            $array["attributes"] = $attributes->transform($domainObject, $request, $this->getType($domainObject));
        }
    }

    /**
     * @param array $array
     * @param mixed $domainObject
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \WoohooLabs\Yin\JsonApi\Schema\Included $included
     * @param string $baseRelationshipPath
     */
    private function transformRelationshipsObject(
        array &$array,
        $domainObject,
        RequestInterface $request,
        Included $included,
        $baseRelationshipPath
    ) {
        $relationships = $this->getRelationships($domainObject);

        if ($relationships !== null) {
            $array["relationships"] = $relationships->transform(
                $domainObject,
                $request,
                $included,
                $this->getType($domainObject),
                $baseRelationshipPath
            );
        }
    }
}
