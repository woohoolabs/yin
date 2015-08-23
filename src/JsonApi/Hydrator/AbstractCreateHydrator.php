<?php
namespace WoohooLabs\Yin\JsonApi\Hydrator;

use WoohooLabs\Yin\JsonApi\Request\RequestInterface;

abstract class AbstractCreateHydrator
{
    use HydratorTrait;
    use CreateHydratorTrait;

    /**
     * Alias for \WoohooLabs\Yin\JsonApi\Hydrator\CreateHydratorTrait::hydrateForCreate()
     *
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param mixed $domainObject
     * @return mixed
     * @throws \WoohooLabs\Yin\JsonApi\Exception\ResourceTypeMissing
     * @see \WoohooLabs\Yin\JsonApi\Hydrator\CreateHydratorTrait::hydrateForCreate()
     */
    public function hydrate(RequestInterface $request, $domainObject)
    {
        return $this->hydrateForCreate($request, $domainObject);
    }
}
