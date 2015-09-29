<?php
namespace WoohooLabs\Yin\JsonApi\Hydrator;

use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;

interface HydratorInterface
{
    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface $exceptionFactory
     * @param mixed $domainObject
     * @return mixed
     * @throws \WoohooLabs\Yin\JsonApi\Exception\ResourceTypeMissing
     */
    public function hydrate(RequestInterface $request, ExceptionFactoryInterface $exceptionFactory, $domainObject);
}
