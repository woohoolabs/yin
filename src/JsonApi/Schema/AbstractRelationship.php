<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

use WoohooLabs\Yin\JsonApi\Request\Request;
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
     * @param mixed $data
     * @param \WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformerInterface $resourceTransformer
     */
    public function __construct($data, ResourceTransformerInterface $resourceTransformer)
    {
        $this->data = $data;
        $this->resourceTransformer = $resourceTransformer;
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\Request $request
     * @param \WoohooLabs\Yin\JsonApi\Schema\Included $included
     * @param string $baseRelationshipPath
     * @param string $relationshipName
     * @return array
     */
    abstract protected function transformData(
        Request $request,
        Included $included,
        $baseRelationshipPath,
        $relationshipName
    );

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\Request $request
     * @param \WoohooLabs\Yin\JsonApi\Schema\Included $included
     * @param string $resourceType
     * @param string $baseRelationshipPath
     * @param string $relationshipName
     * @return array|null
     */
    public function transform(
        Request $request,
        Included $included,
        $resourceType,
        $baseRelationshipPath,
        $relationshipName
    ) {
        $relationship = null;

        $data = $this->transformData($request, $included, $baseRelationshipPath, $relationshipName);

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
            $relationship["data"] = $data;
        }

        return $relationship;
    }

    /**
     * @param mixed $resource
     * @param \WoohooLabs\Yin\JsonApi\Request\Request $request
     * @param \WoohooLabs\Yin\JsonApi\Schema\Included $included
     * @param string $baseRelationshipPath
     * @param string $relationshipName
     * @return array
     */
    protected function transformResource(
        $resource,
        Request $request,
        Included $included,
        $baseRelationshipPath,
        $relationshipName
    ) {
        if ($request->isIncludedRelationship($baseRelationshipPath, $relationshipName)) {
            $included->addIncludedResource(
                $this->resourceTransformer->transformToResource(
                    $resource,
                    $request,
                    $included,
                    $baseRelationshipPath
                )
            );
        }

        return $this->resourceTransformer->transformToResourceIdentifier($resource);
    }
}
