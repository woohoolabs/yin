<?php

declare(strict_types=1);

namespace Devleand\Yin\JsonApi\Hydrator;

use Devleand\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use Devleand\Yin\JsonApi\Exception\JsonApiExceptionInterface;
use Devleand\Yin\JsonApi\Exception\RelationshipNotExists;
use Devleand\Yin\JsonApi\Exception\ResourceTypeMissing;
use Devleand\Yin\JsonApi\Request\JsonApiRequestInterface;

abstract class AbstractUpdateHydrator implements HydratorInterface, UpdateRelationshipHydratorInterface
{
    use HydratorTrait;
    use UpdateHydratorTrait;

    /**
     * Alias for UpdateHydratorTrait::hydrateForUpdate()
     * @see UpdateHydratorTrait::hydrateForUpdate()
     * @param mixed $domainObject
     * @return mixed
     * @throws ResourceTypeMissing|JsonApiExceptionInterface
     */
    public function hydrate(JsonApiRequestInterface $request, ExceptionFactoryInterface $exceptionFactory, $domainObject)
    {
        $domainObject = $this->hydrateForUpdate($request, $exceptionFactory, $domainObject);

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
