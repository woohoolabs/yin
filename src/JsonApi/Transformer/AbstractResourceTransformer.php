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
     * @return array
     */
    abstract protected function getRelationships($resource);

    /**
     * @param \WoohooLabs\Yin\JsonApi\Schema\Included $included
     * @param mixed $resource
     */
    abstract protected function addIncluded(Included $included, $resource);

    /**
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractDocument $document
     * @param mixed $resource
     * @param \WoohooLabs\Yin\JsonApi\Request\Criteria $criteria
     * @param \WoohooLabs\Yin\JsonApi\Schema\Link $selfLink
     * @param \WoohooLabs\Yin\JsonApi\Schema\Link $relatedLink
     * @return array
     */
    public function transform(
        AbstractDocument $document,
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
        $this->transformLinks($result, $resource, $selfLink, $relatedLink);

        // ATTRIBUTES
        $this->transformAttributes($result, $resource);

        //RELATIONSHIPS
        $this->transformRelationships($result, $resource);

        //INCLUDED

        return $result;
    }

    /**
     * @param array $array
     * @param mixed $resource
     * @param \WoohooLabs\Yin\JsonApi\Schema\Link $selfLink
     * @param \WoohooLabs\Yin\JsonApi\Schema\Link $relatedLink
     */
    private function transformLinks(array &$array, $resource, Link $selfLink, Link $relatedLink)
    {
        $links = $this->getLinks($resource);
        if ($links !== null) {
            $links->setSelf($selfLink);
            $links->setRelated($relatedLink);
        }
        $this->addOptionalTransformedItemToArray($array, "links", $links);
    }

    /**
     * @param array $array
     * @param $resource
     */
    private function transformAttributes(array &$array, $resource)
    {
        $attributes = $this->getAttributes($resource);
        $this->addOptionalItemToArray($array, "attributes", $attributes);
    }

    /**
     * @param array $array
     * @param $resource
     */
    private function transformRelationships(array &$array, $resource)
    {
        $relationships = $this->getRelationships($resource);
        $this->addOptionalItemToArray($array, "relationships", $relationships);
    }
}
