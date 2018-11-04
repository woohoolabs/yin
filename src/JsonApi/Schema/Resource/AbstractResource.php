<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema\Resource;

use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformation;
use WoohooLabs\Yin\TransformerTrait;

abstract class AbstractResource implements ResourceInterface
{
    use TransformerTrait;

    /**
     * @var RequestInterface
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
     * @param mixed $object
     */
    public function initializeTransformation(ResourceTransformation $transformation): void
    {
        $this->request = $transformation->request;
        $this->object = $transformation->object;
        $this->exceptionFactory = $transformation->exceptionFactory;
    }

    public function clearTransformation(): void
    {
        $this->request = null;
        $this->object = null;
        $this->exceptionFactory = null;
    }
}
