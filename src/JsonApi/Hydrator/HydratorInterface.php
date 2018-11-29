<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Hydrator;

use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Exception\JsonApiExceptionInterface;
use WoohooLabs\Yin\JsonApi\Exception\ResourceTypeMissing;
use WoohooLabs\Yin\JsonApi\Request\JsonApiRequestInterface;

interface HydratorInterface
{
    /**
     * @param mixed $domainObject
     * @return mixed
     * @throws ResourceTypeMissing|JsonApiExceptionInterface
     */
    public function hydrate(JsonApiRequestInterface $request, ExceptionFactoryInterface $exceptionFactory, $domainObject);
}
