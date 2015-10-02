<?php
namespace WoohooLabs\Yin\JsonApi\Exception;

use WoohooLabs\Yin\JsonApi\Schema\Error;
use WoohooLabs\Yin\JsonApi\Schema\ErrorSource;

class ClientGeneratedIdNotSupported extends JsonApiException
{
    /**
     * @var string
     */
    protected $clientGeneratedId;

    /**
     * @param string|null $clientGeneratedId
     */
    public function __construct($clientGeneratedId)
    {
        parent::__construct(
            "Client generated ID " . ($clientGeneratedId ? "'$clientGeneratedId' " : "") .
            "is not supported!"
        );
        $this->clientGeneratedId = $clientGeneratedId;
    }

    /**
     * @inheritDoc
     */
    protected function getErrors()
    {
        return [
            Error::create()
                ->setStatus(403)
                ->setCode("CLIENT_GENERATED_ID_NOT_SUPPORTED")
                ->setTitle("Client generated ID is not supported")
                ->setDetail($this->getMessage())
                ->setSource(ErrorSource::fromPointer("/data/id"))
        ];
    }

    /**
     * @return string|null
     */
    public function getClientGeneratedId()
    {
        return $this->clientGeneratedId;
    }
}
