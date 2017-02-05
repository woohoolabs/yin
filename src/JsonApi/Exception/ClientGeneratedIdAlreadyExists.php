<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Exception;

use WoohooLabs\Yin\JsonApi\Schema\Error;
use WoohooLabs\Yin\JsonApi\Schema\ErrorSource;

class ClientGeneratedIdAlreadyExists extends JsonApiException
{
    /**
     * @var string
     */
    protected $clientGeneratedId;

    public function __construct(string $clientGeneratedId)
    {
        parent::__construct("Client generated ID '$clientGeneratedId' already exists!");
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
                ->setSource(ErrorSource::fromPointer("/data/id"))
        ];
    }

    public function getClientGeneratedId(): string
    {
        return $this->clientGeneratedId;
    }
}
