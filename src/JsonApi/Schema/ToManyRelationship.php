<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformerInterface;

class ToManyRelationship extends AbstractRelationship
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
        parent::__construct($data, $resourceTransformer, $data, $resourceTransformer);
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \WoohooLabs\Yin\JsonApi\Schema\Included $included
     * @param string $baseRelationshipPath
     * @param string $relationshipName
     * @return array
     */
    protected function transformData(
        RequestInterface $request,
        Included $included,
        $baseRelationshipPath,
        $relationshipName
    ) {
        if ($this->data === null || $this->resourceTransformer === null) {
            return [];
        }

        $result = [];
        foreach ($this->data as $item) {
            $result[] = $this->transformResource(
                $item,
                $request,
                $included,
                $baseRelationshipPath,
                $relationshipName
            );
        }
        return $result;
    }
}
