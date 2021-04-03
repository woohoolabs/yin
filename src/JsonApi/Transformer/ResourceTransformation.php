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
    public ?ResourceInterface $resource;
    /** @var mixed */
    public $object;
    public string $resourceType;
    public JsonApiRequestInterface $request;
    public string $basePath;
    public string $requestedRelationshipName;
    public string $currentRelationshipName;
    public ExceptionFactoryInterface $exceptionFactory;
    public ?array $result = null;

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
