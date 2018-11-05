<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema\Relationship;

use WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface;
use WoohooLabs\Yin\JsonApi\Schema\Link\RelationshipLinks;
use WoohooLabs\Yin\JsonApi\Schema\MetaTrait;
use WoohooLabs\Yin\JsonApi\Schema\Resource\ResourceInterface;
use WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformation;
use WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformer;

abstract class AbstractRelationship
{
    use MetaTrait;

    /**
     * @var RelationshipLinks
     */
    protected $links;

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
     * @var ResourceInterface|null
     */
    protected $resource;

    /**
     * @internal
     */
    abstract protected function transformData(
        ResourceTransformation $transformation,
        ResourceTransformer $resourceTransformer,
        DataInterface $data,
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
    public function __construct(
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
    public function omitWhenNotIncluded()
    {
        $this->omitDataWhenNotIncluded = true;

        return $this;
    }

    /**
     * @internal
     * @return mixed
     */
    protected function getData()
    {
        return $this->isCallableData ? \call_user_func($this->data, $this) : $this->data;
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
        if ($transformation->request->isIncludedField($transformation->resourceType, $transformation->currentRelationshipName) === false) {
            return null;
        }

        $relationshipObject = [];

        if ($this->links !== null) {
            $relationshipObject["links"] = $this->links->transform();
        }

        if (empty($this->meta) === false) {
            $relationshipObject["meta"] = $this->meta;
        }

        if (($transformation->requestedRelationshipName && $transformation->currentRelationshipName !== $transformation->requestedRelationshipName) ||
            ($transformation->request->isIncludedRelationship($transformation->basePath, $transformation->currentRelationshipName, $defaultRelationships) === false && $this->omitDataWhenNotIncluded)
        ) {
            return $relationshipObject;
        }

        $dataMember = $this->transformData($transformation, $resourceTransformer, $data, $defaultRelationships);
        if ($dataMember !== false) {
            $relationshipObject["data"] = $dataMember;
        }

        return $relationshipObject;
    }

    /**
     * @internal
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

        if ($transformation->request->isIncludedRelationship(
            $transformation->basePath,
            $transformation->currentRelationshipName,
            $defaultRelationships
        )) {
            $resource = $resourceTransformer->transformToResourceObject($relationshipTransformation, $data);
            if ($resource !== null) {
                $data->addIncludedResource($resource);
            }
        }

        return $resourceTransformer->transformToResourceIdentifier($relationshipTransformation);
    }
}
