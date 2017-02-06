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

    /**
     * @inheritDoc
     */
    protected function validateType($data, ExceptionFactoryInterface $exceptionFactory)
    {
    }

    /**
     * @inheritDoc
     */
    protected function validateClientGeneratedId(
        $clientGeneratedId,
        RequestInterface $request,
        ExceptionFactoryInterface $exceptionFactory
    ) {
        if ($this->isClientGeneratedIdException) {
            throw $exceptionFactory->createClientGeneratedIdNotSupportedException($request, $clientGeneratedId);
        }
    }

    /**
     * @inheritDoc
     */
    protected function generateId()
    {
        return $this->generatedId;
    }

    /**
     * @inheritDoc
     */
    protected function setId($domainObject, $id)
    {
        $domainObject["id"] = $id;

        return $domainObject;
    }

    /**
     * @inheritDoc
     */
    protected function hydrateAttributes($domainObject, array $data)
    {
        return $domainObject;
    }

    /**
     * @inheritDoc
     */
    protected function hydrateRelationships($domainObject, array $data, ExceptionFactoryInterface $exceptionFactory)
    {
        return $domainObject;
    }
}
