<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Hydrator;

use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Exception\JsonApiExceptionInterface;
use WoohooLabs\Yin\JsonApi\Exception\RelationshipNotExists;
use WoohooLabs\Yin\JsonApi\Exception\ResourceTypeMissing;
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
     * @param mixed $domainObject
     * @return mixed
     * @throws ResourceTypeMissing|JsonApiExceptionInterface
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
     * @param mixed $domainObject
     * @return mixed
     * @throws RelationshipNotExists|JsonApiExceptionInterface
     */
    public function hydrateRelationship(
        string $relationship,
        RequestInterface $request,
        ExceptionFactoryInterface $exceptionFactory,
        $domainObject
    ) {
        return $this->hydrateForRelationshipUpdate($relationship, $request, $exceptionFactory, $domainObject);
    }
}
