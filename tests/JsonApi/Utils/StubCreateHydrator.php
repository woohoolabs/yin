<?php
namespace WoohooLabsTest\Yin\JsonApi\Utils;

use WoohooLabs\Yin\JsonApi\Exception\ClientGeneratedIdNotSupported;
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

    /**
     * @param bool $isClientGeneratedIdException
     * @param string $generatedId
     */
    public function __construct($isClientGeneratedIdException = false, $generatedId = "")
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
            throw new ClientGeneratedIdNotSupported($clientGeneratedId);
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
