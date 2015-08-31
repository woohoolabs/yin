<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
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
     * @var bool
     */
    protected $isDefault;

    /**
     * @param array $meta
     * @param \WoohooLabs\Yin\JsonApi\Schema\Links|null $links
     * @param mixed $data
     * @param \WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformerInterface|null $resourceTransformer
     * @param bool $isDefault
     */
    public function __construct(
        array $meta = [],
        Links $links = null,
        $data = null,
        ResourceTransformerInterface $resourceTransformer = null,
        $isDefault = false
    ) {
        $this->meta = $meta;
        $this->links = $links;
        $this->data = $data;
        $this->resourceTransformer = $resourceTransformer;
        $this->isDefault = $isDefault;
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \WoohooLabs\Yin\JsonApi\Schema\Included $included
     * @param string $baseRelationshipPath
     * @param string $relationshipName
     * @return array
     */
    abstract protected function transformData(
        RequestInterface $request,
        Included $included,
        $baseRelationshipPath,
        $relationshipName
    );

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
     * @return bool
     */
    public function isDefault()
    {
        return $this->isDefault;
    }

    /**
     * @param bool $isDefault
     * @return AbstractRelationship
     */
    public function setDefault($isDefault)
    {
        $this->isDefault = $isDefault;
        return $this;
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \WoohooLabs\Yin\JsonApi\Schema\Included $included
     * @param string $resourceType
     * @param string $baseRelationshipPath
     * @param string $relationshipName
     * @return array|null
     */
    public function transform(
        RequestInterface $request,
        Included $included,
        $resourceType,
        $baseRelationshipPath,
        $relationshipName
    ) {
        $relationship = null;

        $data = $this->transformData($request, $included, $baseRelationshipPath, $relationshipName);

        if ($request->isIncludedField($resourceType, $relationshipName) || $this->isDefault === true) {
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
            $relationship["data"] = $data;
        }

        return $relationship;
    }

    /**
     * @param mixed $domainObject
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \WoohooLabs\Yin\JsonApi\Schema\Included $included
     * @param string $baseRelationshipPath
     * @param string $relationshipName
     * @return array
     */
    protected function transformResource(
        $domainObject,
        RequestInterface $request,
        Included $included,
        $baseRelationshipPath,
        $relationshipName
    ) {
        if ($request->isIncludedRelationship($baseRelationshipPath, $relationshipName) || $this->isDefault === true) {
            $included->addIncludedResource(
                $this->resourceTransformer->transformToResource(
                    $domainObject,
                    $request,
                    $included,
                    $baseRelationshipPath
                )
            );
        }

        return $this->resourceTransformer->transformToResourceIdentifier($domainObject);
    }
}
