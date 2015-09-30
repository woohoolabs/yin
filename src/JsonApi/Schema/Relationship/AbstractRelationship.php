<?php
namespace WoohooLabs\Yin\JsonApi\Schema\Relationship;

use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface;
use WoohooLabs\Yin\JsonApi\Schema\Links;
use WoohooLabs\Yin\JsonApi\Schema\LinksTrait;
use WoohooLabs\Yin\JsonApi\Schema\MetaTrait;
use WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformerInterface;

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
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface $data
     * @param string $baseRelationshipPath
     * @param string $relationshipName
     * @param array $defaultRelationships
     * @return array
     */
    abstract protected function transformData(
        RequestInterface $request,
        DataInterface $data,
        $baseRelationshipPath,
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
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface $data
     * @param string $resourceType
     * @param string $baseRelationshipPath
     * @param string $relationshipName
     * @param array $defaultRelationships
     * @return array|null
     */
    public function transform(
        RequestInterface $request,
        DataInterface $data,
        $resourceType,
        $baseRelationshipPath,
        $relationshipName,
        array $defaultRelationships
    ) {
        $relationship = null;

        $transformedData = $this->transformData(
            $request,
            $data,
            $baseRelationshipPath,
            $relationshipName,
            $defaultRelationships
        );

        if ($request->isIncludedField($resourceType, $relationshipName)) {
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
     * @param mixed $domainObject
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface $data
     * @param string $baseRelationshipPath
     * @param string $relationshipName
     * @param array $defaultRelationships
     * @return array
     */
    protected function transformResource(
        $domainObject,
        RequestInterface $request,
        DataInterface $data,
        $baseRelationshipPath,
        $relationshipName,
        array $defaultRelationships
    ) {
        if ($request->isIncludedRelationship($baseRelationshipPath, $relationshipName, $defaultRelationships)) {
            $data->addIncludedResource($this->resourceTransformer->transformToResource(
                $domainObject,
                $request,
                $data,
                $baseRelationshipPath
            ));
        }

        return $this->resourceTransformer->transformToResourceIdentifier($domainObject);
    }
}
