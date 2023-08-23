<?php

declare(strict_types=1);

namespace Devleand\Yin\JsonApi\Hydrator;

use Devleand\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use Devleand\Yin\JsonApi\Exception\JsonApiExceptionInterface;
use Devleand\Yin\JsonApi\Exception\ResourceTypeMissing;
use Devleand\Yin\JsonApi\Request\JsonApiRequestInterface;

interface HydratorInterface
{
    /**
     * @param mixed $domainObject
     * @return mixed
     * @throws ResourceTypeMissing|JsonApiExceptionInterface
     */
    public function hydrate(JsonApiRequestInterface $request, ExceptionFactoryInterface $exceptionFactory, $domainObject);
}
