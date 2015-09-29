<?php
namespace WoohooLabs\Yin\JsonApi\Hydrator;

use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;

abstract class AbstractCreateHydrator implements HydratorInterface
{
    use HydratorTrait;
    use CreateHydratorTrait;

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param mixed $domainObject
     * @param \WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface $exceptionFactory
     * @return mixed
     * @throws \WoohooLabs\Yin\JsonApi\Exception\ResourceTypeMissing
     * @see \WoohooLabs\Yin\JsonApi\Hydrator\CreateHydratorTrait::hydrateForCreate()
     */
    public function hydrate(RequestInterface $request, $domainObject, ExceptionFactoryInterface $exceptionFactory)
    {
        return $this->hydrateForCreate($request, $domainObject, $exceptionFactory);
    }
}
