<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Transformer;

use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Schema\Resource\ResourceInterface;

/**
 * @internal
 */
class ResourceTransformation
{
    /**
     * @var ResourceInterface
     */
    public $resource;

    /**
     * @var mixed
     */
    public $object;

    /**
     * @var string
     */
    public $resourceType;

    /**
     * @var RequestInterface
     */
    public $request;

    /**
     * @var string
     */
    public $basePath;

    /**
     * @var string
     */
    public $requestedRelationshipName;

    /**
     * @var string
     */
    public $currentRelationshipName;

    /**
     * @var ExceptionFactoryInterface
     */
    public $exceptionFactory;

    /**
     * @var array|null
     */
    public $result;

    public function __construct(
        ResourceInterface $resource,
        $object,
        string $resourceType,
        RequestInterface $request,
        string $basePath,
        string $requestedRelationshipName,
        string $currentRelationshipName,
        ExceptionFactoryInterface $exceptionFactory
    ) {
        $this->resource = $resource;
        $this->object = $object;
        $this->resourceType = $resourceType;
        $this->request = $request;
        $this->basePath = $basePath;
        $this->requestedRelationshipName = $requestedRelationshipName;
        $this->currentRelationshipName = $currentRelationshipName;
        $this->exceptionFactory = $exceptionFactory;
    }
}
