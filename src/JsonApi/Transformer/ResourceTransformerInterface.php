<?php
namespace WoohooLabs\Yin\JsonApi\Transformer;

use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Schema\Included;

interface ResourceTransformerInterface
{
    /**
     * @param mixed $domainObject
     * @return array|null
     */
    public function transformToResourceIdentifier($domainObject);

    /**
     * @param mixed $domainObject
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \WoohooLabs\Yin\JsonApi\Schema\Included $included
     * @param string $baseRelationshipPath
     * @return array|null
     */
    public function transformToResource(
        $domainObject,
        RequestInterface $request,
        Included $included,
        $baseRelationshipPath = ""
    );

    /**
     * @param mixed $domainObject
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \WoohooLabs\Yin\JsonApi\Schema\Included $included
     * @param string $relationshipName
     * @param string $baseRelationshipPath
     * @return array|null
     */
    public function transformRelationship(
        $domainObject,
        RequestInterface $request,
        Included $included,
        $relationshipName,
        $baseRelationshipPath = ""
    );
}
