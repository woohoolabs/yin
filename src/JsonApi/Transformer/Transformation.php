<?php
namespace WoohooLabs\Yin\JsonApi\Transformer;

use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface;

class Transformation
{
    /**
     * @var \WoohooLabs\Yin\JsonApi\Request\RequestInterface
     */
    public $request;

    /**
     * @var \WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface
     */
    public $data;

    /**
     * @var \WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface
     */
    public $exceptionFactory;

    /**
     * @var string
     */
    public $basePath;

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface $data
     * @param \WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface $exceptionFactory
     * @param string $basePath
     */
    public function __construct(
        RequestInterface $request,
        DataInterface $data,
        ExceptionFactoryInterface $exceptionFactory,
        $basePath
    ) {
        $this->request = $request;
        $this->data = $data;
        $this->exceptionFactory = $exceptionFactory;
        $this->basePath = $basePath;
    }

    /**
     * @return RequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return \WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return \WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface
     */
    public function getExceptionFactory()
    {
        return $this->exceptionFactory;
    }

    /**
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }
}
