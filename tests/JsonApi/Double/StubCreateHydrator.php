<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Double;

use LogicException;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Hydrator\CreateHydratorTrait;
use WoohooLabs\Yin\JsonApi\Request\JsonApiRequestInterface;

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

    /**
     * @var bool
     */
    private $logicException;

    public function __construct(bool $isClientGeneratedIdException, string $generatedId, bool $logicException)
    {
        $this->isClientGeneratedIdException = $isClientGeneratedIdException;
        $this->generatedId = $generatedId;
        $this->logicException = $logicException;
    }

    protected function validateType($data, ExceptionFactoryInterface $exceptionFactory): void
    {
    }

    protected function validateClientGeneratedId(
        string $clientGeneratedId,
        JsonApiRequestInterface $request,
        ExceptionFactoryInterface $exceptionFactory
    ): void {
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

    protected function validateRequest(JsonApiRequestInterface $request): void
    {
        if ($this->logicException) {
            throw new LogicException();
        }
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
