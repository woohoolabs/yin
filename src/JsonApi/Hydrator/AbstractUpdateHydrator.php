<?php
namespace WoohooLabs\Yin\JsonApi\Hydrator;

use WoohooLabs\Yin\JsonApi\Request\RequestInterface;

abstract class AbstractUpdateHydrator implements HydratorInterface
{
    use HydratorTrait;
    use UpdateHydratorTrait;

    /**
     * Alias for \WoohooLabs\Yin\JsonApi\Hydrator\UpdateHydratorTrait::hydrateForUpdate()
     *
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param mixed $domainObject
     * @return mixed
     * @throws \WoohooLabs\Yin\JsonApi\Exception\ResourceTypeMissing
     * @see \WoohooLabs\Yin\JsonApi\Hydrator\UpdateHydratorTrait::hydrateForUpdate()
     */
    public function hydrate(RequestInterface $request, $domainObject)
    {
        return $this->hydrateForUpdate($request, $domainObject);
    }
}
