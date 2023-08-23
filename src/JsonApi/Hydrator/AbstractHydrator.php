<?php

declare(strict_types=1);

namespace Devleand\Yin\JsonApi\Hydrator;

use Devleand\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use Devleand\Yin\JsonApi\Exception\JsonApiExceptionInterface;
use Devleand\Yin\JsonApi\Exception\RelationshipNotExists;
use Devleand\Yin\JsonApi\Exception\ResourceTypeMissing;
use Devleand\Yin\JsonApi\Request\JsonApiRequestInterface;

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
     * @param mixed $domainObject
     * @return mixed
     * @throws ResourceTypeMissing|JsonApiExceptionInterface
     */
    public function hydrate(JsonApiRequestInterface $request, ExceptionFactoryInterface $exceptionFactory, $domainObject)
    {
        if ($request->getMethod() === "POST") {
            $domainObject = $this->hydrateForCreate($request, $exceptionFactory, $domainObject);
        } elseif ($request->getMethod() === "PATCH") {
            $domainObject = $this->hydrateForUpdate($request, $exceptionFactory, $domainObject);
        }

        $this->validateDomainObject($request, $exceptionFactory, $domainObject);

        return $domainObject;
    }

    /**
     * @param mixed $domainObject
     * @return mixed
     * @throws RelationshipNotExists|JsonApiExceptionInterface
     */
    public function hydrateRelationship(
        string $relationship,
        JsonApiRequestInterface $request,
        ExceptionFactoryInterface $exceptionFactory,
        $domainObject
    ) {
        return $this->hydrateForRelationshipUpdate($relationship, $request, $exceptionFactory, $domainObject);
    }

    /**
     * You can validate the domain object after it has been hydrated from the request.
     * @param mixed $domainObject
     */
    protected function validateDomainObject(
        JsonApiRequestInterface $request,
        ExceptionFactoryInterface $exceptionFactory,
        $domainObject
    ): void {
    }
}
