<?php
namespace WoohooLabs\Yin\JsonApi\Schema\Relationship;

use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface;
use WoohooLabs\Yin\JsonApi\Schema\Links;
use WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformerInterface;

class ToOneRelationship extends AbstractRelationship
{
    use RelationshipFactoryTrait;

    /**
     * @param array $meta
     * @param \WoohooLabs\Yin\JsonApi\Schema\Links|null $links
     * @param array $data
     * @param \WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformerInterface|null $resourceTransformer
     */
    public function __construct(
        array $meta = [],
        Links $links = null,
        array $data = [],
        ResourceTransformerInterface $resourceTransformer = null
    ) {
        parent::__construct($meta, $links, $data, $resourceTransformer);
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface $data
     * @param string $baseRelationshipPath
     * @param string $relationshipName
     * @param array $defaultRelationships
     * @return array
     */
    protected function transformData(
        RequestInterface $request,
        DataInterface $data,
        $baseRelationshipPath,
        $relationshipName,
        array $defaultRelationships
    ) {
        if ($this->data === null || $this->resourceTransformer === null) {
            return null;
        }

        return $this->transformResource(
            $this->data,
            $request,
            $data,
            $baseRelationshipPath,
            $relationshipName,
            $defaultRelationships
        );
    }
}
