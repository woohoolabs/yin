<?php
namespace WoohooLabs\Yin\JsonApi\Transformer;

use WoohooLabs\Yin\JsonApi\Request\Request;
use WoohooLabs\Yin\JsonApi\Schema\Included;

interface ResourceTransformerInterface
{
    /**
     * @param mixed $resource
     * @return array|null
     */
    public function transformToResourceIdentifier($resource);

    /**
     * @param mixed $resource
     * @param \WoohooLabs\Yin\JsonApi\Request\Request $request
     * @param \WoohooLabs\Yin\JsonApi\Schema\Included $included
     * @param string $baseRelationshipPath
     * @return array|null
     */
    public function transformToResource($resource, Request $request, Included $included, $baseRelationshipPath = "");

    /**
     * @param mixed $resource
     * @param \WoohooLabs\Yin\JsonApi\Request\Request $request
     * @param \WoohooLabs\Yin\JsonApi\Schema\Included $included
     * @param string $relationshipName
     * @param string $baseRelationshipPath
     * @return array|null
     */
    public function transformRelationship($resource, Request $request, Included $included, $relationshipName, $baseRelationshipPath = "");
}
