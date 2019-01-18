<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Transformer;

use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Request\JsonApiRequestInterface;
use WoohooLabs\Yin\JsonApi\Schema\Resource\ResourceInterface;

/**
 * @internal
 */
class ResourceTransformation
{
    /**
     * @var ResourceInterface|null
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
     * @var JsonApiRequestInterface
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

    /**
     * @param mixed $object
     */
    public function __construct(
        ?ResourceInterface $resource,
        $object,
        string $resourceType,
        JsonApiRequestInterface $request,
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
