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
    private $data;

    /**
     * @var bool
     */
    protected $isCallableData;

    /**
     * @var bool
     */
    protected $omitDataWhenNotIncluded;

    /**
     * @var \WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformerInterface
     */
    protected $resourceTransformer;

    /**
     * @param \WoohooLabs\Yin\JsonApi\Transformer\Transformation $transformation
     * @param string $relationshipName
     * @param array $defaultRelationships
     * @return array|null
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
        $this->isCallableData = false;
        $this->omitDataWhenNotIncluded = false;
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
        $this->isCallableData = false;
        $this->resourceTransformer = $resourceTransformer;

        return $this;
    }

    /**
     * @param mixed $data
     * @param \WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformerInterface $resourceTransformer
     * @return $this
     */
    public function setDataAsCallable(callable $data, ResourceTransformerInterface $resourceTransformer)
    {
        $this->data = $data;
        $this->isCallableData = true;
        $this->resourceTransformer = $resourceTransformer;

        return $this;
    }

    public function omitWhenNotIncluded()
    {
        $this->omitDataWhenNotIncluded = true;

        return $this;
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Transformer\Transformation $transformation
     * @param string $resourceType
     * @param string $relationshipName
     * @param array $defaultRelationships
     * @param array $additionalMeta
     * @return array|null
     */
    public function transform(
        Transformation $transformation,
        $resourceType,
        $relationshipName,
        array $defaultRelationships,
        array $additionalMeta = []
    ) {
        $relationship = null;

        if (
            $transformation->request->isIncludedRelationship(
                $transformation->basePath,
                $relationshipName,
                $defaultRelationships
            ) ||
            (
                $transformation->fetchedRelationship === $relationshipName &&
                $this->data &&
                $this->omitDataWhenNotIncluded === false
            )
        ) {
            $transformedData = $this->transformData($transformation, $relationshipName, $defaultRelationships);
        } else {
            $transformedData = false;
        }

        if ($transformation->request->isIncludedField($resourceType, $relationshipName)) {
            $relationship = [];

            // LINKS
            if ($this->links !== null) {
                $relationship["links"] = $this->links->transform();
            }

            // META
            $meta = array_merge($this->meta, $additionalMeta);
            if (empty($meta) === false) {
                $relationship["meta"] = $meta;
            }

            // DATA
            if ($transformedData !== false) {
                $relationship["data"] = $transformedData;
            }
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
            $basePath = $transformation->basePath;
            if ($transformation->basePath !== "") {
                $transformation->basePath .= ".";
            }
            $transformation->basePath .= $relationshipName;

            $transformation->data->addIncludedResource(
                $this->resourceTransformer->transformToResource($transformation, $domainObject)
            );

            $transformation->basePath = $basePath;
        }

        return $this->resourceTransformer->transformToResourceIdentifier($domainObject);
    }

    /**
     * @return mixed
     */
    protected function retrieveData()
    {
        return $this->isCallableData ? call_user_func($this->data, $this) : $this->data;
    }
}
