<?php
namespace WoohooLabs\Yin\JsonApi\Transformer;

use WoohooLabs\Yin\JsonApi\Request\Request;
use WoohooLabs\Yin\JsonApi\Schema\Included;
use WoohooLabs\Yin\TransformerTrait;

abstract class AbstractResourceTransformer implements ResourceTransformerInterface
{
    use TransformerTrait;

    /**
     * @param mixed $resource
     * @return string
     */
    abstract public function getType($resource);

    /**
     * @param mixed $resource
     * @return string
     */
    abstract public function getId($resource);

    /**
     * @param mixed $resource
     * @return array
     */
    abstract public function getMeta($resource);

    /**
     * @param mixed $resource
     * @param string $relationshipPath
     * @return \WoohooLabs\Yin\JsonApi\Schema\Links|null
     */
    abstract public function getLinks($resource, $relationshipPath);

    /**
     * @param mixed $resource
     * @return \WoohooLabs\Yin\JsonApi\Schema\Attributes|null
     */
    abstract public function getAttributes($resource);

    /**
     * @param mixed $resource
     * @return \WoohooLabs\Yin\JsonApi\Schema\Relationships|null
     */
    abstract public function getRelationships($resource);

    /**
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
     * @param mixed $resource
     * @param \WoohooLabs\Yin\JsonApi\Request\Request $request
     * @param \WoohooLabs\Yin\JsonApi\Schema\Included $included
     * @param string $baseRelationshipPath
     * @return array|null
     */
    public function transformToResource($resource, Request $request, Included $included, $baseRelationshipPath = "")
    {
        if ($resource === null) {
            return null;
        }

        $result = $this->transformToResourceIdentifier($resource);

        // LINKS
        $this->transformLinks($result, $resource, $baseRelationshipPath);

        // ATTRIBUTES
        $this->transformAttributes($result, $resource, $request);

        //RELATIONSHIPS
        $this->transformRelationships($result, $resource, $request, $included, $baseRelationshipPath);

        return $result;
    }

    /**
     * @param array $array
     * @param mixed $resource
     * @param string $relationshipPath
     */
    private function transformLinks(array &$array, $resource, $relationshipPath)
    {
        $links = $this->getLinks($resource, $relationshipPath);

        if (empty($links) === false) {
            $array["links"] = $links->transform();
        }
    }

    /**
     * @param array $array
     * @param $resource
     * @param \WoohooLabs\Yin\JsonApi\Request\Request $request
     */
    private function transformAttributes(array &$array, $resource, Request $request)
    {
        $attributes = $this->getAttributes($resource);
        if ($attributes !== null) {
            $array["attributes"] = $attributes->transform($resource, $request, $this->getType($resource));
        }
    }

    /**
     * @param array $array
     * @param mixed $resource
     * @param \WoohooLabs\Yin\JsonApi\Request\Request $request
     * @param \WoohooLabs\Yin\JsonApi\Schema\Included $included
     * @param string $baseRelationshipPath
     */
    private function transformRelationships(
        array &$array,
        $resource,
        Request $request,
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
