<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Hydrator;

use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Exception\JsonApiExceptionInterface;
use WoohooLabs\Yin\JsonApi\Exception\ResourceTypeMissing;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;

abstract class AbstractCreateHydrator implements HydratorInterface
{
    use HydratorTrait;
    use CreateHydratorTrait;

    /**
     * @param mixed $domainObject
     * @return mixed
     * @throws ResourceTypeMissing|JsonApiExceptionInterface
     * @see CreateHydratorTrait::hydrateForCreate()
     */
    public function hydrate(RequestInterface $request, ExceptionFactoryInterface $exceptionFactory, $domainObject)
    {
        return $this->hydrateForCreate($request, $exceptionFactory, $domainObject);
    }
}
