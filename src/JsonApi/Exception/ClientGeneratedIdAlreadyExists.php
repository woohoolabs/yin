<?php

declare(strict_types=1);

namespace Devleand\Yin\JsonApi\Exception;

use Devleand\Yin\JsonApi\Schema\Error\Error;
use Devleand\Yin\JsonApi\Schema\Error\ErrorSource;

class ClientGeneratedIdAlreadyExists extends AbstractJsonApiException
{
    /**
     * @var string
     */
    protected $clientGeneratedId;

    public function __construct(string $clientGeneratedId)
    {
        parent::__construct("Client generated ID '$clientGeneratedId' already exists!", 409);
        $this->clientGeneratedId = $clientGeneratedId;
    }

    protected function getErrors(): array
    {
        return [
            Error::create()
                ->setStatus("409")
                ->setCode("CLIENT_GENERATED_ID_ALREADY_EXISTS")
                ->setTitle("Client generated ID already exists")
                ->setDetail($this->getMessage())
                ->setSource(ErrorSource::fromPointer("/data/id")),
        ];
    }

    public function getClientGeneratedId(): string
    {
        return $this->clientGeneratedId;
    }
}
