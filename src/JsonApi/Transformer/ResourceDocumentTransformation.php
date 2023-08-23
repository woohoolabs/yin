<?php

declare(strict_types=1);

namespace Devleand\Yin\JsonApi\Transformer;

use Devleand\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use Devleand\Yin\JsonApi\Request\JsonApiRequestInterface;
use Devleand\Yin\JsonApi\Schema\Document\ResourceDocumentInterface;

/**
 * @internal
 */
class ResourceDocumentTransformation extends AbstractDocumentTransformation
{
    /**
     * @var ResourceDocumentInterface
     */
    public $document;

    /**
     * @var mixed
     */
    public $object;

    /**
     * @var string
     */
    public $basePath;

    /**
     * @var string
     */
    public $requestedRelationshipName;

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
