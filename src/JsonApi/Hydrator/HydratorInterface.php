<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Hydrator;

use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Exception\ResourceTypeMissing;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;

interface HydratorInterface
{
    /**
     * @param RequestInterface $request
     * @param ExceptionFactoryInterface $exceptionFactory
     * @param mixed $domainObject
     * @return mixed
     * @throws ResourceTypeMissing
     */
    public function hydrate(RequestInterface $request, ExceptionFactoryInterface $exceptionFactory, $domainObject);
}
