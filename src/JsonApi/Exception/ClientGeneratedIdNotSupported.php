<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Exception;

use WoohooLabs\Yin\JsonApi\Schema\Error;
use WoohooLabs\Yin\JsonApi\Schema\ErrorSource;

class ClientGeneratedIdNotSupported extends JsonApiException
{
    /**
     * @var string
     */
    protected $clientGeneratedId;

    public function __construct(string $clientGeneratedId)
    {
        parent::__construct(
            "Client generated ID " . ($clientGeneratedId ? "'$clientGeneratedId' " : "") .
            "is not supported!"
        );
        $this->clientGeneratedId = $clientGeneratedId;
    }

    protected function getErrors(): array
    {
        return [
            Error::create()
                ->setStatus("403")
                ->setCode("CLIENT_GENERATED_ID_NOT_SUPPORTED")
                ->setTitle("Client generated ID is not supported")
                ->setDetail($this->getMessage())
                ->setSource(ErrorSource::fromPointer("/data/id"))
        ];
    }

    public function getClientGeneratedId(): string
    {
        return $this->clientGeneratedId;
    }
}
