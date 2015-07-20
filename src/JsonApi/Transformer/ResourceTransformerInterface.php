<?php
namespace WoohooLabs\Yin\JsonApi\Transformer;

use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
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
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \WoohooLabs\Yin\JsonApi\Schema\Included $included
     * @param string $baseRelationshipPath
     * @return array|null
     */
    public function transformToResource(
        $resource,
        RequestInterface $request,
        Included $included,
        $baseRelationshipPath = ""
    );

    /**
     * @param mixed $resource
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \WoohooLabs\Yin\JsonApi\Schema\Included $included
     * @param string $relationshipName
     * @param string $baseRelationshipPath
     * @return array|null
     */
    public function transformRelationship(
        $resource,
        RequestInterface $request,
        Included $included,
        $relationshipName,
        $baseRelationshipPath = ""
    );
}
