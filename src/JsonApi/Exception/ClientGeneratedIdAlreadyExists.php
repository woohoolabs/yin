<?php
namespace WoohooLabs\Yin\JsonApi\Exception;

use WoohooLabs\Yin\JsonApi\Schema\Error;
use WoohooLabs\Yin\JsonApi\Schema\ErrorSource;

class ClientGeneratedIdAlreadyExists extends JsonApiException
{
    /**
     * @var string
     */
    private $clientGeneratedId;

    /**
     * @param string|null $clientGeneratedId
     */
    public function __construct($clientGeneratedId)
    {
        parent::__construct("Client generated ID '$clientGeneratedId' already exists!");
        $this->clientGeneratedId = $clientGeneratedId;
    }

    /**
     * @inheritDoc
     */
    protected function getErrors()
    {
        return [
            Error::create()
                ->setStatus(409)
                ->setCode("CLIENT_GENERATED_ID_ALREADY_EXISTS")
                ->setTitle("Client generated ID already exists")
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
