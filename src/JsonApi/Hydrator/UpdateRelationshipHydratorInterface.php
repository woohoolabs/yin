<?php

declare(strict_types=1);

namespace Devleand\Yin\JsonApi\Hydrator;

use Devleand\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use Devleand\Yin\JsonApi\Exception\JsonApiExceptionInterface;
use Devleand\Yin\JsonApi\Exception\RelationshipNotExists;
use Devleand\Yin\JsonApi\Request\JsonApiRequestInterface;

interface UpdateRelationshipHydratorInterface
{
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
    );
}
