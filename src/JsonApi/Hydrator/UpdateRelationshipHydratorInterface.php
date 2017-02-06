<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Hydrator;

use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Exception\RelationshipNotExists;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;

interface UpdateRelationshipHydratorInterface
{
    /**
     * @param mixed $domainObject
     * @return mixed
     * @throws RelationshipNotExists
     */
    public function hydrateRelationship(
        string $relationship,
        RequestInterface $request,
        ExceptionFactoryInterface $exceptionFactory,
        $domainObject
    );
}
