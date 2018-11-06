<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Transformer;

use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Schema\Document\ResourceDocumentInterface;

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

    public function __construct(
        ResourceDocumentInterface $document,
        $object,
        RequestInterface $request,
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
