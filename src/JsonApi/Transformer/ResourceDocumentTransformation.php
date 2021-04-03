<?php

declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Transformer;

use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Request\JsonApiRequestInterface;
use WoohooLabs\Yin\JsonApi\Schema\Document\ResourceDocumentInterface;

/**
 * @internal
 */
class ResourceDocumentTransformation extends AbstractDocumentTransformation
{
    /** @var ResourceDocumentInterface */
    public $document;
    /** @var mixed */
    public $object;
    public string $basePath;
    public string $requestedRelationshipName;

    /**
     * @param mixed $object
     */
    public function __construct(
        ResourceDocumentInterface $document,
        $object,
        JsonApiRequestInterface $request,
        string $basePath,
        string $relationpshipName,
        array $additionalMeta,
        ExceptionFactoryInterface $exceptionFactory
    ) {
        parent::__construct($document, $request, $additionalMeta, $exceptionFactory);
        $this->object = $object;
        $this->basePath = $basePath;
        $this->requestedRelationshipName = $relationpshipName;
    }
}
