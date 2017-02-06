<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Double;

use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Hydrator\CreateHydratorTrait;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;

class StubCreateHydrator
{
    use CreateHydratorTrait;

    /**
     * @var bool
     */
    private $isClientGeneratedIdException;

    /**
     * @var string
     */
    private $generatedId;

    public function __construct(bool $isClientGeneratedIdException = false, string $generatedId = "")
    {
        $this->isClientGeneratedIdException = $isClientGeneratedIdException;
        $this->generatedId = $generatedId;
    }

    protected function validateType($data, ExceptionFactoryInterface $exceptionFactory)
    {
    }

    protected function validateClientGeneratedId(
        string $clientGeneratedId,
        RequestInterface $request,
        ExceptionFactoryInterface $exceptionFactory
    ) {
        if ($this->isClientGeneratedIdException) {
            throw $exceptionFactory->createClientGeneratedIdNotSupportedException($request, $clientGeneratedId);
        }
    }

    protected function generateId(): string
    {
        return $this->generatedId;
    }

    protected function setId($domainObject, string $id)
    {
        $domainObject["id"] = $id;

        return $domainObject;
    }

    protected function hydrateAttributes($domainObject, array $data)
    {
        return $domainObject;
    }

    protected function hydrateRelationships($domainObject, array $data, ExceptionFactoryInterface $exceptionFactory)
    {
        return $domainObject;
    }
}
