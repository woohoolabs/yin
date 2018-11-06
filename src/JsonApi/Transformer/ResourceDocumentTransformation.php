<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Transformer;

use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Schema\Document\ResourceDocumentInterface;

/**
 * @internal
 */
class ResourceDocumentTransformation
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
     * @var array
     */
    public $additionalMeta;

    /**
     * @var ExceptionFactoryInterface
     */
    public $exceptionFactory;

    /**
     * @var array
     */
    public $result = [];

    public function __construct(
        ResourceDocumentInterface $document,
        $object,
        RequestInterface $request,
        string $basePath,
        string $relationpshipName,
        array $additionalMeta,
        ExceptionFactoryInterface $exceptionFactory
    ) {
        $this->document = $document;
        $this->object = $object;
        $this->request = $request;
        $this->basePath = $basePath;
        $this->requestedRelationshipName = $relationpshipName;
        $this->additionalMeta = $additionalMeta;
        $this->exceptionFactory = $exceptionFactory;
    }
}
