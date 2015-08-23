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
     * @param mixed $resource
     * @return string
     */
    abstract public function getType($resource);

    /**
     * Provides information about the "meta" section of the current resource.
     *
     * The method returns the ID of the current resource which should be a UUID.
     *
     * @param mixed $resource
     * @return string
     */
    abstract public function getId($resource);

    /**
     * Provides information about the "meta" section of the current resource.
     *
     * The method returns an array of non-standard meta information about the resource. If
     * this array is empty, the section won't appear in the response.
     *
     * @param mixed $resource
     * @return array
     */
    abstract public function getMeta($resource);

    /**
     * Provides information about the "links" section of the current resource.
     *
     * The method returns a new Links schema object if you want to provide linkage
     * data about the resource or null if it should be omitted from the response.
     *
     * @param mixed $resource
     * @return \WoohooLabs\Yin\JsonApi\Schema\Links|null
     */
    abstract public function getLinks($resource);

    /**
     * Provides information about the "attributes" section of the current resource.
     *
     * The method returns a new Attributes schema object if you want the section to
     * appear in the response of null if it should be omitted.
     *
     * @param mixed $resource
     * @return \WoohooLabs\Yin\JsonApi\Schema\Attributes|null
     */
    abstract public function getAttributes($resource);

    /**
     * Provides information about the "relationships" section of the current resource.
     *
     * The method returns a new Relationships schema object if you want the section to
     * appear in the response of null if it should be omitted.
     *
     * @param mixed $resource
     * @return \WoohooLabs\Yin\JsonApi\Schema\Relationships|null
     */
    abstract public function getRelationships($resource);

    /**
     * Transforms the original resource to a JSON API resource identifier.
     *
     * @param mixed $resource
     * @return array|null
     */
    public function transformToResourceIdentifier($resource)
    {
        if ($resource === null) {
            return null;
        }

        $result = [
            "type" => $this->getType($resource),
            "id" => $this->getId($resource),
        ];

        // META
        $meta = $this->getMeta($resource);
        if (empty($meta) === false) {
            $result["meta"] = $meta;
        }

        return $result;
    }

    /**
     * Transforms the original resource to a JSON API resource.
     *
     * @param mixed $resource
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \WoohooLabs\Yin\JsonApi\Schema\Included $included
     * @param string $baseRelationshipPath
     * @return array|null
     */
    public function transformToResource(
        $resource,
        RequestInterface $request,
        Included $included,
        $baseRelationshipPath = ""
    ) {
        $result = $this->transformToResourceIdentifier($resource);

        if ($result === null) {
            return null;
        }

        // Links
        $this->transformLinksObject($result, $resource);

        // Attributes
        $this->transformAttributesObject($result, $resource, $request);

        // Relationships
        $this->transformRelationshipsObject($result, $resource, $request, $included, $baseRelationshipPath);

        return $result;
    }

    /**
     * Transforms a relationship with a name of $relationshipName of the original resource to a JSON API relationship.
     *
     * @param mixed $resource
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \WoohooLabs\Yin\JsonApi\Schema\Included $included
     * @param string $relationshipName
     * @param string $baseRelationshipPath
     * @return array|null
     */
    public function transformRelationship(
        $resource,
        RequestInterface $request,
        Included $included,
        $relationshipName,
        $baseRelationshipPath = ""
    ) {
        $relationships = $this->getRelationships($resource);

        if ($relationships === null) {
            return null;
        }

        return $relationships->transformRelationship(
            $relationshipName,
            $resource,
            $request,
            $included,
            $this->getType($resource),
            $baseRelationshipPath
        );
    }

    /**
     * @param array $array
     * @param mixed $resource
     */
    private function transformLinksObject(array &$array, $resource)
    {
        $links = $this->getLinks($resource);

        if (empty($links) === false) {
            $array["links"] = $links->transform();
        }
    }

    /**
     * @param array $array
     * @param $resource
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     */
    private function transformAttributesObject(array &$array, $resource, RequestInterface $request)
    {
        $attributes = $this->getAttributes($resource);
        if ($attributes !== null) {
            $array["attributes"] = $attributes->transform($resource, $request, $this->getType($resource));
        }
    }

    /**
     * @param array $array
     * @param mixed $resource
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \WoohooLabs\Yin\JsonApi\Schema\Included $included
     * @param string $baseRelationshipPath
     */
    private function transformRelationshipsObject(
        array &$array,
        $resource,
        RequestInterface $request,
        Included $included,
        $baseRelationshipPath
    ) {
        $relationships = $this->getRelationships($resource);

        if ($relationships !== null) {
            $array["relationships"] = $relationships->transform(
                $resource,
                $request,
                $included,
                $this->getType($resource),
                $baseRelationshipPath
            );
        }
    }
}
