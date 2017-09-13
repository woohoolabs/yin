<?php
declare(strict_types=1);

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
     * @var ResourceTransformerInterface
     */
    protected $resourceTransformer;

    abstract protected function transformData(
        Transformation $transformation,
        string $relationshipName,
        array $defaultRelationships
    ): ?array;

    /**
     * @return static
     */
    public static function create()
    {
        return new static();
    }

    /**
     * @return static
     */
    public static function createWithMeta(array $meta)
    {
        return new static($meta);
    }

    /**
     * @return static
     */
    public static function createWithLinks(Links $links)
    {
        return new static([], $links);
    }

    /**
     * @return static
     */
    public static function createWithData(array $data, ResourceTransformerInterface $resourceTransformer)
    {
        return new static([], null, $data, $resourceTransformer);
    }

    /**
     * @param mixed $data
     */
    public function __construct(
        array $meta = [],
        ?Links $links = null,
        $data = [],
        ?ResourceTransformerInterface $resourceTransformer = null
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
     * @return $this
     */
    public function setDataAsCallable(callable $data, ResourceTransformerInterface $resourceTransformer)
    {
        $this->data = $data;
        $this->isCallableData = true;
        $this->resourceTransformer = $resourceTransformer;

        return $this;
    }

    /**
     * @return $this
     */
    public function omitWhenNotIncluded()
    {
        $this->omitDataWhenNotIncluded = true;

        return $this;
    }

    public function transform(
        Transformation $transformation,
        string $resourceType,
        string $relationshipName,
        array $defaultRelationships,
        array $additionalMeta = []
    ): ?array {
        $relationship = null;

        if (
            (
                $transformation->fetchedRelationship === $relationshipName &&
                $this->data &&
                $this->omitDataWhenNotIncluded === false
            ) ||
            $transformation->request->isIncludedRelationship(
                $transformation->basePath,
                $relationshipName,
                $defaultRelationships
            )
        ) {
            $transformedData = $this->transformData($transformation, $relationshipName, $defaultRelationships);
        } else {
            $transformedData = false;
        }

        if ($transformation->request->isIncludedField($resourceType, $relationshipName)) {
            $relationship = [];

            // Links
            if ($this->links !== null) {
                $relationship["links"] = $this->links->transform();
            }

            // Meta
            $meta = array_merge($this->meta, $additionalMeta);
            if (empty($meta) === false) {
                $relationship["meta"] = $meta;
            }

            // Data
            if ($transformedData !== false) {
                $relationship["data"] = $transformedData;
            }
        }

        return $relationship;
    }

    /**
     * @param mixed $domainObject
     */
    protected function transformResource(
        Transformation $transformation,
        $domainObject,
        string $relationshipName,
        array $defaultRelationships
    ): array {
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

            $resource = $this->resourceTransformer->transformToResource($transformation, $domainObject);
            if ($resource) {
                $transformation->data->addIncludedResource($resource);
            }

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
