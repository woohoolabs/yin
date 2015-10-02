<?php
namespace WoohooLabs\Yin\JsonApi\Schema\Relationship;

use WoohooLabs\Yin\JsonApi\Schema\Links;
use WoohooLabs\Yin\JsonApi\Schema\LinksTrait;
use WoohooLabs\Yin\JsonApi\Schema\MetaTrait;
use WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformerInterface;
use WoohooLabs\Yin\JsonApi\Transformer\Transformation;

abstract class AbstractRelationship
{
    use LinksTrait;
    use MetaTrait;

    /**
     * @var mixed
     */
    protected $data;

    /**
     * @var \WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformerInterface
     */
    protected $resourceTransformer;

    /**
     * @param \WoohooLabs\Yin\JsonApi\Transformer\Transformation $transformation
     * @param string $relationshipName
     * @param array $defaultRelationships
     * @return array
     */
    abstract protected function transformData(
        Transformation $transformation,
        $relationshipName,
        array $defaultRelationships
    );

    /**
     * @param array $meta
     * @param \WoohooLabs\Yin\JsonApi\Schema\Links|null $links
     * @param mixed $data
     * @param \WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformerInterface|null $resourceTransformer
     */
    public function __construct(
        array $meta = [],
        Links $links = null,
        $data = null,
        ResourceTransformerInterface $resourceTransformer = null
    ) {
        $this->meta = $meta;
        $this->links = $links;
        $this->data = $data;
        $this->resourceTransformer = $resourceTransformer;
    }

    /**
     * @param mixed $data
     * @param \WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformerInterface $resourceTransformer
     * @return $this
     */
    public function setData($data, ResourceTransformerInterface $resourceTransformer)
    {
        $this->data = $data;
        $this->resourceTransformer = $resourceTransformer;

        return $this;
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Transformer\Transformation $transformation
     * @param string $resourceType
     * @param string $relationshipName
     * @param array $defaultRelationships
     * @return array|null
     */
    public function transform(
        Transformation $transformation,
        $resourceType,
        $relationshipName,
        array $defaultRelationships
    ) {
        $relationship = null;
        $transformedData = $this->transformData($transformation, $relationshipName, $defaultRelationships);

        if ($transformation->request->isIncludedField($resourceType, $relationshipName)) {
            $relationship = [];

            // LINKS
            if ($this->links !== null) {
                $relationship["links"] = $this->links->transform();
            }

            // META
            if (empty($this->meta) === false) {
                $relationship["meta"] = $this->meta;
            }

            // DATA
            $relationship["data"] = $transformedData;
        }

        return $relationship;
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Transformer\Transformation $transformation
     * @param mixed $domainObject
     * @param string $relationshipName
     * @param array $defaultRelationships
     * @return array
     */
    protected function transformResource(
        Transformation $transformation,
        $domainObject,
        $relationshipName,
        array $defaultRelationships
    ) {
        if ($transformation->request->isIncludedRelationship(
            $transformation->basePath,
            $relationshipName,
            $defaultRelationships
        )) {
            $transformation->data->addIncludedResource(
                $this->resourceTransformer->transformToResource($transformation, $domainObject)
            );
        }

        return $this->resourceTransformer->transformToResourceIdentifier($domainObject);
    }
}
