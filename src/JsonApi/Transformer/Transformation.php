<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Transformer;

use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface;

class Transformation
{
    /**
     * @var RequestInterface
     */
    public $request;

    /**
     * @var DataInterface
     */
    public $data;

    /**
     * @var ExceptionFactoryInterface
     */
    public $exceptionFactory;

    /**
     * @var string
     */
    public $basePath;

    /**
     * @var string
     */
    public $fetchedRelationship = "";

    public function __construct(
        RequestInterface $request,
        DataInterface $data,
        ExceptionFactoryInterface $exceptionFactory,
        string $basePath
    ) {
        $this->request = $request;
        $this->data = $data;
        $this->exceptionFactory = $exceptionFactory;
        $this->basePath = $basePath;
    }

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    public function getData(): DataInterface
    {
        return $this->data;
    }

    public function getExceptionFactory(): ExceptionFactoryInterface
    {
        return $this->exceptionFactory;
    }

    public function getBasePath(): string
    {
        return $this->basePath;
    }

    public function getFetchedRelationship(): string
    {
        return $this->fetchedRelationship;
    }

    public function setFetchedRelationship(string $fetchedRelationship): void
    {
        $this->fetchedRelationship = $fetchedRelationship;
    }
}
