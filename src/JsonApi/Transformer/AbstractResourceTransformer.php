<?php
namespace WoohooLabs\Yin\JsonApi\Transformer;

use WoohooLabs\Yin\JsonApi\Request\Criteria;
use WoohooLabs\Yin\JsonApi\Schema\Included;

abstract class AbstractResourceTransformer implements ResourceTransformerInterface
{
    use TransformerTrait;

    /**
     * @param mixed $resource
     * @return string
     */
    abstract protected function getType($resource);

    /**
     * @param mixed $resource
     * @return string
     */
    abstract protected function getId($resource);

    /**
     * @param mixed $resource
     * @return array
     */
    abstract protected function getMeta($resource);

    /**
     * @param mixed $resource
     * @param string $relationshipPath
     * @return \WoohooLabs\Yin\JsonApi\Schema\Links|null
     */
    abstract protected function getLinks($resource, $relationshipPath);

    /**
     * @param mixed $resource
     * @return \WoohooLabs\Yin\JsonApi\Schema\Attributes
     */
    abstract protected function getAttributes($resource);

    /**
     * @param mixed $resource
     * @return \WoohooLabs\Yin\JsonApi\Schema\Relationships
     */
    abstract protected function getRelationships($resource);

    /**
     * @param mixed $resource
     * @return array
     */
    public function transformToResourceIdentifier($resource)
    {
        $result = [
            "type" => $this->getType($resource),
            "id" => $this->getId($resource),
        ];

        // META
        $this->addOptionalItemToArray($result, "meta", $this->getMeta($resource));

        return $result;
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractCompoundDocument $document
     * @param mixed $resource
     * @param \WoohooLabs\Yin\JsonApi\Request\Criteria $criteria
     * @param string $relationshipPath
     * @return array
     */
    public function transformToResource(
        AbstractCompoundDocument $document,
        $resource,
        Criteria $criteria,
        $relationshipPath = ""
    ) {
        $result = $this->transformToResourceIdentifier($resource);

        // LINKS
        $this->transformLinks($result, $resource, $criteria, $relationshipPath);

        // ATTRIBUTES
        $this->transformAttributes($result, $resource, $criteria);

        //RELATIONSHIPS
        $this->transformRelationships($result, $resource, $criteria, $document->getIncluded(), $relationshipPath);

        return $result;
    }

    /**
     * @param array $array
     * @param mixed $resource
     * @param \WoohooLabs\Yin\JsonApi\Request\Criteria $criteria
     * @param string $relationshipPath
     */
    private function transformLinks(array &$array, $resource, Criteria $criteria, $relationshipPath)
    {
        $links = $this->getLinks($resource, $relationshipPath);

        $this->addOptionalTransformedItemToArray($resource, $criteria, $array, "links", $links);
    }

    /**
     * @param array $array
     * @param $resource
     * @param \WoohooLabs\Yin\JsonApi\Request\Criteria $criteria
     */
    private function transformAttributes(array &$array, $resource, Criteria $criteria)
    {
        $attributes = $this->getAttributes($resource);
        if ($attributes !== null) {
            $array["attributes"] = $attributes->transform($resource, $criteria, $this->getType($resource));
        }
    }

    /**
     * @param array $array
     * @param mixed $resource
     * @param \WoohooLabs\Yin\JsonApi\Request\Criteria $criteria
     * @param \WoohooLabs\Yin\JsonApi\Schema\Included $included
     * @param string $relationshipPath
     */
    private function transformRelationships(
        array &$array,
        $resource,
        Criteria $criteria,
        Included $included,
        $relationshipPath
    ) {
        $relationships = $this->getRelationships($resource);

        if ($relationships !== null) {
            $array["relationships"] = $relationships->transform($resource, $criteria, $included, $relationshipPath);
        }
    }
}
