<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Hydrator;

use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Exception\JsonApiExceptionInterface;
use WoohooLabs\Yin\JsonApi\Exception\RelationshipNotExists;
use WoohooLabs\Yin\JsonApi\Exception\ResourceTypeMissing;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;

abstract class AbstractUpdateHydrator implements HydratorInterface, UpdateRelationshipHydratorInterface
{
    use HydratorTrait;
    use UpdateHydratorTrait;

    /**
     * Alias for UpdateHydratorTrait::hydrateForUpdate()
     *
     * @param mixed $domainObject
     * @return mixed
     * @throws ResourceTypeMissing|JsonApiExceptionInterface
     * @see UpdateHydratorTrait::hydrateForUpdate()
     */
    public function hydrate(RequestInterface $request, ExceptionFactoryInterface $exceptionFactory, $domainObject)
    {
        return $this->hydrateForUpdate($request, $exceptionFactory, $domainObject);
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
