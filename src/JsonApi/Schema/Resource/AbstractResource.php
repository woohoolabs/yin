<?php

declare(strict_types=1);

namespace Devleand\Yin\JsonApi\Schema\Resource;

use Devleand\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use Devleand\Yin\JsonApi\Request\JsonApiRequestInterface;
use Devleand\Yin\TransformerTrait;

abstract class AbstractResource implements ResourceInterface
{
    use TransformerTrait;

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
     * @internal
     * @param mixed $object
     */
    public function initializeTransformation(JsonApiRequestInterface $request, $object, ExceptionFactoryInterface $exceptionFactory): void
    {
        $this->request = $request;
        $this->object = $object;
        $this->exceptionFactory = $exceptionFactory;
    }

    /**
     * @internal
     */
    public function clearTransformation(): void
    {
        $this->request = null;
        $this->object = null;
        $this->exceptionFactory = null;
    }
}
