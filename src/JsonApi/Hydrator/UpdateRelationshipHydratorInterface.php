<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Hydrator;

use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Exception\JsonApiExceptionInterface;
use WoohooLabs\Yin\JsonApi\Exception\RelationshipNotExists;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;

interface UpdateRelationshipHydratorInterface
{
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
    );
}
