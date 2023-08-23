<?php

declare(strict_types=1);

namespace Devleand\Yin\JsonApi\Schema\Document;

use Devleand\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use Devleand\Yin\JsonApi\Request\JsonApiRequestInterface;
use Devleand\Yin\JsonApi\Transformer\ResourceDocumentTransformation;

abstract class AbstractResourceDocument implements ResourceDocumentInterface
{
    /**
     * @var JsonApiRequestInterface
     */
    protected $request;

    /**
     * @var mixed
     */
    protected $object;

    /**
     * @var ExceptionFactoryInterface
     */
    protected $exceptionFactory;

    /**
     * @var array
     */
    protected $additionalMeta = [];

    /**
     * @internal
     */
    public function initializeTransformation(ResourceDocumentTransformation $transformation): void
    {
        $this->request = $transformation->request;
        $this->object = $transformation->object;
        $this->exceptionFactory = $transformation->exceptionFactory;
        $this->additionalMeta = $transformation->additionalMeta;
    }

    /**
     * @internal
     */
    public function clearTransformation(): void
    {
    }
}
