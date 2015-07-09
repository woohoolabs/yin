<?php
namespace WoohooLabs\Yin\JsonApi\Transformer;

use WoohooLabs\Yin\JsonApi\Request\Criteria;
use WoohooLabs\Yin\JsonApi\Schema\Included;
use WoohooLabs\Yin\JsonApi\Schema\Link;

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
     * @return \WoohooLabs\Yin\JsonApi\Schema\Links|null
     */
    abstract protected function getLinks($resource);

    /**
     * @param mixed $resource
     * @return array
     */
    abstract protected function getAttributes($resource);

    /**
     * @param mixed $resource
     * @return \WoohooLabs\Yin\JsonApi\Schema\Relationships
     */
    abstract protected function getRelationships($resource);

    /**
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractCompoundDocument $document
     * @param mixed $resource
     * @param \WoohooLabs\Yin\JsonApi\Request\Criteria $criteria
     * @param \WoohooLabs\Yin\JsonApi\Schema\Link $selfLink
     * @param \WoohooLabs\Yin\JsonApi\Schema\Link $relatedLink
     * @return array
     */
    public function transform(
        AbstractCompoundDocument $document,
        $resource,
        Criteria $criteria,
        Link $selfLink = null,
        Link $relatedLink = null
    ) {
        $result = [
            "type" => $this->getType($resource),
            "id" => $this->getId($resource),
        ];

        // META
        $this->addOptionalItemToArray($result, "meta", $this->getMeta($resource));

        // LINKS
        $this->transformLinks($criteria, $result, $resource, $selfLink, $relatedLink);

        // ATTRIBUTES
        $this->transformAttributes($criteria, $result, $resource);

        //RELATIONSHIPS
        $this->transformRelationships($document->getIncluded(), $criteria, $result, $resource);

        return $result;
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\Criteria $criteria
     * @param array $array
     * @param mixed $resource
     * @param \WoohooLabs\Yin\JsonApi\Schema\Link $selfLink
     * @param \WoohooLabs\Yin\JsonApi\Schema\Link $relatedLink
     */
    private function transformLinks(Criteria $criteria, array &$array, $resource, Link $selfLink, Link $relatedLink)
    {
        $links = $this->getLinks($resource);
        if ($links !== null) {
            $links->setSelf($selfLink);
            $links->setRelated($relatedLink);
        }
        $this->addOptionalTransformedItemToArray($criteria, $array, "links", $links);
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\Criteria $criteria
     * @param array $array
     * @param $resource
     */
    private function transformAttributes(Criteria $criteria, array &$array, $resource)
    {
        $attributes = $this->getAttributes($resource);
        $this->addOptionalTransformedItemToArray($criteria, $array, "attributes", $attributes);
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Schema\Included $included
     * @param \WoohooLabs\Yin\JsonApi\Request\Criteria $criteria
     * @param array $array
     * @param $resource
     */
    private function transformRelationships(Included $included, Criteria $criteria, array &$array, $resource)
    {
        $relationships = $this->getRelationships($resource);
        $this->addOptionalIncludedTransformedItemToArray($included, $criteria, $array, "relationships", $relationships);
    }
}
