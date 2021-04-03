<?php

declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema\Relationship;

use WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface;
use WoohooLabs\Yin\JsonApi\Schema\Link\RelationshipLinks;
use WoohooLabs\Yin\JsonApi\Schema\MetaTrait;
use WoohooLabs\Yin\JsonApi\Schema\Resource\ResourceInterface;
use WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformation;
use WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformer;

use function call_user_func;

abstract class AbstractRelationship
{
    use MetaTrait;

    protected ?RelationshipLinks $links;
    /** @var mixed */
    private $data;
    protected bool $isCallableData;
    protected bool $omitDataWhenNotIncluded;
    protected ?ResourceInterface $resource;

    /**
     * @internal
     *
     * @return array|false|null
     */
    abstract protected function transformData(
        ResourceTransformation $transformation,
        ResourceTransformer $resourceTransformer,
        DataInterface $data,
        array $defaultRelationships
    );

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
    public static function createWithLinks(?RelationshipLinks $links)
    {
        return new static([], $links);
    }

    /**
     * @return static
     */
    public static function createWithData(array $data, ResourceInterface $resource)
    {
        return new static([], null, $data, $resource);
    }

    /**
     * @param mixed $data
     */
    final public function __construct(
        array $meta = [],
        ?RelationshipLinks $links = null,
        $data = null,
        ?ResourceInterface $resource = null
    ) {
        $this->meta = $meta;
        $this->links = $links;
        $this->data = $data;
        $this->isCallableData = false;
        $this->omitDataWhenNotIncluded = false;
        $this->resource = $resource;
    }

    public function getLinks(): ?RelationshipLinks
    {
        return $this->links;
    }

    /**
     * @return $this
     */
    public function setLinks(RelationshipLinks $links)
    {
        $this->links = $links;

        return $this;
    }

    /**
     * @param mixed $data
     * @return $this
     */
    public function setData($data, ResourceInterface $resource)
    {
        $this->data = $data;
        $this->isCallableData = false;
        $this->resource = $resource;

        return $this;
    }

    /**
     * @return $this
     */
    public function setDataAsCallable(callable $callableData, ResourceInterface $resource)
    {
        $this->data = $callableData;
        $this->isCallableData = true;
        $this->resource = $resource;

        return $this;
    }

    /**
     * @return $this
     */
    public function omitDataWhenNotIncluded()
    {
        $this->omitDataWhenNotIncluded = true;

        return $this;
    }

    /**
     * @internal
     *
     * @return mixed
     */
    protected function getData()
    {
        return $this->isCallableData ? call_user_func($this->data, $this) : $this->data;
    }

    /**
     * @internal
     */
    public function transform(
        ResourceTransformation $transformation,
        ResourceTransformer $resourceTransformer,
        DataInterface $data,
        array $defaultRelationships
    ): ?array {
        $requestedRelationshipName = $transformation->requestedRelationshipName;
        $currentRelationshipName = $transformation->currentRelationshipName;
        $basePath = $transformation->basePath;

        $isCurrentRelationship = $requestedRelationshipName !== "" && $currentRelationshipName === $requestedRelationshipName;
        $isIncludedField = $transformation->request->isIncludedField($transformation->resourceType, $currentRelationshipName);
        $isIncludedRelationship = $transformation->request->isIncludedRelationship($basePath, $currentRelationshipName, $defaultRelationships);

        // The relationship is not needed at all
        if ($isCurrentRelationship === false && $isIncludedField === false && $isIncludedRelationship === false) {
            return null;
        }

        // Transform the relationship data
        $dataMember = false;
        if (
            ($isCurrentRelationship === true || $isIncludedRelationship === true || $this->omitDataWhenNotIncluded === false) &&
            ($isCurrentRelationship === true || $requestedRelationshipName === "")
        ) {
            $dataMember = $this->transformData($transformation, $resourceTransformer, $data, $defaultRelationships);
        }

        // The relationship field is not included
        if ($isIncludedField === false) {
            return null;
        }

        // Transform the relationship link because the relationship field is included
        $relationshipObject = [];

        if ($this->links !== null) {
            $relationshipObject["links"] = $this->links->transform();
        }

        if (empty($this->meta) === false) {
            $relationshipObject["meta"] = $this->meta;
        }

        if ($dataMember !== false) {
            $relationshipObject["data"] = $dataMember;
        }

        return $relationshipObject;
    }

    /**
     * @internal
     *
     * @param mixed $object
     */
    protected function transformResourceIdentifier(
        ResourceTransformation $transformation,
        ResourceTransformer $resourceTransformer,
        DataInterface $data,
        $object,
        array $defaultRelationships
    ): ?array {
        $relationshipTransformation = clone $transformation;
        $relationshipTransformation->resourceType = "";
        $relationshipTransformation->resource = $this->resource;
        $relationshipTransformation->object = $object;

        $basePath = $transformation->basePath;
        $basePath .= ($basePath !== "" ? "." : "") . $relationshipTransformation->currentRelationshipName;
        $relationshipTransformation->basePath = $basePath;

        if (
            $transformation->request->isIncludedRelationship(
                $transformation->basePath,
                $transformation->currentRelationshipName,
                $defaultRelationships
            )
        ) {
            $resource = $resourceTransformer->transformToResourceObject($relationshipTransformation, $data);
            if ($resource !== null) {
                $data->addIncludedResource($resource);
            }
        }

        return $resourceTransformer->transformToResourceIdentifier($relationshipTransformation);
    }
}
