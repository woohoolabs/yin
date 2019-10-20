<?php

declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema\Document;

use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Request\JsonApiRequestInterface;
use WoohooLabs\Yin\JsonApi\Transformer\ResourceDocumentTransformation;

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
