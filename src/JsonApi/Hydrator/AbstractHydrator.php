<?php
namespace WoohooLabs\Yin\JsonApi\Hydrator;

use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;

abstract class AbstractHydrator implements HydratorInterface, UpdateRelationshipHydratorInterface
{
    use HydratorTrait;
    use CreateHydratorTrait;
    use UpdateHydratorTrait;

    /**
     * Hydrates the domain object from the request based on the request method.
     *
     * If the request method is POST then the domain object is hydrated
     * as a create. If it is a PATCH request then the domain object is
     * hydrated as an update.
     *
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface $exceptionFactory
     * @param mixed $domainObject
     * @return mixed
     * @throws \WoohooLabs\Yin\JsonApi\Exception\ResourceTypeMissing
     */
    public function hydrate(RequestInterface $request, ExceptionFactoryInterface $exceptionFactory, $domainObject)
    {
        if ($request->getMethod() === "POST") {
            $domainObject = $this->hydrateForCreate($request, $exceptionFactory, $domainObject);
        } elseif ($request->getMethod() === "PATCH") {
            $domainObject = $this->hydrateForUpdate($request, $exceptionFactory, $domainObject);
        }

        return $domainObject;
    }

    /**
     * @param string $relationship
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface $exceptionFactory
     * @param mixed $domainObject
     * @return mixed
     * @throws \WoohooLabs\Yin\JsonApi\Exception\RelationshipNotExists
     */
    public function hydrateRelationship(
        $relationship,
        RequestInterface $request,
        ExceptionFactoryInterface $exceptionFactory,
        $domainObject
    ) {
        $this->hydrateForRelationshipUpdate($relationship, $request, $exceptionFactory, $domainObject);
    }
}
